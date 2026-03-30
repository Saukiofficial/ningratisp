<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\Invoices;
use App\Models\Packages;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DiscountService
{
    /**
     * Return active, applicable discounts for a given customer / package / date.
     *
     * @return array<int, Discount>
     */
    public function getApplicableDiscounts(Customer $customer, ?Packages $package = null, ?Carbon $date = null): array
    {
        $date = $date ?? now();

        return Discount::query()
            ->active()
            // ->where('auto_apply', true)
            ->where(function ($q) use ($customer) {
                $q->whereNull('customer_category')
                    ->orWhere('customer_category', $customer->customer_category);
            })
            ->when($package, fn($q) => $q->where(function ($w) use ($package) {
                $w->whereNull('package_id')->orWhere('package_id', $package->id);
            }))
            ->get()
            ->filter(fn(Discount $d) => $d->isWithinDateRange($date))
            ->values()
            ->all();
    }

    /**
     * Calculate and set discount on invoice according to attached discount_id and/or auto rules.
     */
    public function calculateInvoiceDiscount(Invoices $invoice): Invoices
    {
        $customer = $invoice->customerPackage->customer;

        // For 'normal' customers, a discount must be manually applied.
        if ($customer->customer_category === Discount::TYPE_NORMAL) {
            $discount = $customer->primaryDiscount ?? $invoice->discount;
            if ($discount && $this->validateDiscountForCustomer($discount, $customer)) {
                return $this->applyDiscountToInvoice($invoice, $discount);
            }

            return $invoice;
        }

        // For 'free_forever' customers, find and apply the best auto-applicable discount.
        if ($customer->customer_category === Discount::TYPE_FREE_FOREVER) {
            $package = $customer->activePackage->package ?? null;
            $discounts = $this->getApplicableDiscounts($customer, $package);

            if (empty($discounts)) {
                return $invoice;
            }

            // Simple strategy: apply the first valid discount. Could be enhanced to find the "best" one.
            foreach ($discounts as $discount) {
                if ($this->validateDiscountForCustomer($discount, $customer)) {
                    return $this->applyDiscountToInvoice($invoice, $discount);
                }
            }
        }

        return $invoice;
    }

    public function validateDiscountForCustomer(Discount $discount, Customer $customer, ?Invoices $currentInvoice = null, bool $forUsage = false): array
    {
        if (! $discount->canClaimed && ! $forUsage) {
            return [
                'valid' => false,
                'message' => 'Diskon ini tidak dapat diklaim saat ini.',
            ];
        }

        if (! $discount->is_active || ! $discount->isWithinDateRange()) {
            return [
                'valid' => false,
                'message' => 'Diskon ini sudah tidak aktif atau telah melewati masa berlaku.',
            ];
        }

        if ($discount->customer_category && $discount->customer_category !== $customer->customer_category) {
            return [
                'valid' => false,
                'message' => 'Diskon ini hanya berlaku untuk kategori pelanggan tertentu.',
            ];
        }

        if ($discount->usage_limit !== null && $discount->used_count >= $discount->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Kuota diskon ini sudah habis.',
            ];
        }

        if (! empty($discount->max_per_user)) {
            $claimsCount = $customer->discounts()->where('discount_id', $discount->id)->count();

            // Count invoices where this discount is used and PAID
            $paidUsageCount = $customer->invoices()
                ->where('invoices.status', Invoices::STATUS_PAID)
                ->where('discount_id', $discount->id)
                ->count();

            // Also count UNPAID invoices that are using this discount (excluding the current one)
            // to prevent over-reserving if we want to be strict.
            $activeAssignmentCount = $customer->invoices()
                ->where('invoices.status', '!=', Invoices::STATUS_PAID)
                ->where('discount_id', $discount->id)
                ->when($currentInvoice, fn($q) => $q->where('invoices.id', '!=', $currentInvoice->id))
                ->count();

            $totalUsage = $paidUsageCount + $activeAssignmentCount;

            // If we are validating for payment, we check against max_per_user
            if ($forUsage) {
                if ($paidUsageCount >= $discount->max_per_user) {
                    return [
                        'valid' => false,
                        'message' => 'Kamu sudah mencapai batas penggunaan diskon ini.',
                    ];
                }
            } else {
                // If validating for a NEW claim, we check claimsCount vs max_per_user
                if ($claimsCount >= $discount->max_per_user) {
                    return [
                        'valid' => false,
                        'message' => 'Kamu sudah mencapai batas klaim diskon ini.',
                    ];
                }
            }
        }

        return [
            'valid' => true,
            'message' => 'Diskon berhasil diterapkan.',
        ];
    }

    public function applyDiscountToInvoice(Invoices $invoice, Discount $discount, $recordDiscountUsage = false): Invoices
    {
        $subtotal = $invoice->subtotal;
        $discountAmount = 0;

        if ($discount->type === Discount::PERCENTAGE) {
            $discountAmount = ($subtotal * $discount->value) / 100;
            if (! empty(floatval($discount->max_discount_amount)) && $discountAmount > $discount->max_discount_amount) {
                $discountAmount = $discount->max_discount_amount;
            }
        } elseif ($discount->type === Discount::FIXED_AMOUNT) {
            $discountAmount = $discount->value;
        }

        // Ensure discount doesn't exceed subtotal
        $discountAmount = min($subtotal, $discountAmount);

        $invoice->discount_id = $discount->id;
        $invoice->discount_amount = $discountAmount;
        $invoice->recalculateTotals();
        $invoice->save();

        return $invoice;
    }

    public function finalizeDiscountUsage(Invoices $invoice): void
    {
        if (! $invoice->discount_id || $invoice->status !== Invoices::STATUS_PAID) {
            return;
        }

        DB::transaction(function () use ($invoice) {
            $discount = $invoice->discount;
            $customer = $invoice->customerPackage->customer;

            // Increment total usage count on the discount itself
            $discount->increment('used_count');

            // Mark the claim as inactive in the pivot table
            $customer->discounts()
                ->wherePivot('discount_id', $invoice->discount_id)
                ->wherePivot('is_active', true)
                ->first()
                ?->pivot
                ->update(['is_active' => false]);
        });
    }

    /**
     * This is a placeholder for a potential different flow, e.g., manually attaching a discount to a customer profile.
     * The main logic for invoice-time discounts is in `calculateInvoiceDiscount`.
     */
    public function applyAutoDiscounts(Customer $customer): void
    {
        // Placeholder: attach auto discounts to customer via pivot if rules match.
        // This could be used for long-term discounts not tied to a single invoice event.
    }
}
