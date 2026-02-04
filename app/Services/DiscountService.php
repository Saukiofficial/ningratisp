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

    public function validateDiscountForCustomer(Discount $discount, Customer $customer): bool
    {

        if (!$discount->canClaimed) {
            return false;
        }

        if (! $discount->is_active || ! $discount->isWithinDateRange()) {
            return false;
        }

        // Check customer category if specified on the discount
        if ($discount->customer_category && $discount->customer_category !== $customer->customer_category) {
            return false;
        }

        // Check overall usage limit
        if ($discount->usage_limit !== null && $discount->used_count >= $discount->usage_limit) {
            return false;
        }

        // Check per-customer usage limit
        if (!empty($discount->max_per_user)) {
            $customerUsage = $customer->discounts()->where('discount_id', $discount->id)->count();
            if ($customerUsage >= $discount->max_per_user) {
                return false;
            }
        }

        return true;
    }

    public function applyDiscountToInvoice(Invoices $invoice, Discount $discount, $recordDiscountUsage = true): Invoices
    {
        $subtotal = $invoice->subtotal;
        $discountAmount = 0;

        if ($discount->type === Discount::PERCENTAGE) {
            $discountAmount = ($subtotal * $discount->value) / 100;
            if (!empty(floatval($discount->max_discount_amount)) && $discountAmount > $discount->max_discount_amount) {
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

        if ($recordDiscountUsage) {
            $this->recordDiscountUsage(
                $discount,
                $invoice->customerPackage->customer,
                'Discount applied to invoice : ' . $invoice->invoice_number
            );
        }

        return $invoice;
    }

    public function customerApplyDiscountToInvoice(Invoices $invoice, Discount $discount): Invoices
    {
        return $this->applyDiscountToInvoice(
            $invoice,
            $discount,
            false
        );
    }

    private function recordDiscountUsage(Discount $discount, Customer $customer, ?string $notes = null): void
    {
        DB::transaction(function () use ($discount, $customer, $notes) {
            // Increment total usage count on the discount itself
            $discount->increment('used_count');

            // Create or update the record in the customer_discounts pivot table
            $customer->discounts()->attach($discount->id, [
                'applied_at' => now(),
                'is_active' => true, // Or based on specific logic
                'notes' => $notes
            ]);
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
