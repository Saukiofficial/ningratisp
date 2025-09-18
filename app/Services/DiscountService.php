<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\Invoices;
use App\Models\Packages;
use Illuminate\Support\Carbon;

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
            ->when($package, fn($q) => $q->where(function ($w) use ($package) {
                $w->whereNull('package_id')->orWhere('package_id', $package->id);
            }))
            ->get()
            ->filter(fn(Discount $d) => $d->isWithinDateRange())
            ->values()
            ->all();
    }

    /**
     * Calculate and set discount on invoice according to attached discount_id and/or auto rules.
     */
    public function calculateInvoiceDiscount(Invoices $invoice): Invoices
    {
        // Placeholder: compute based on invoice subtotal and discount
        // The detailed logic will be implemented in the next step.
        return $invoice;
    }

    public function validateDiscountForCustomer(Discount $discount, Customer $customer): bool
    {
        // Extend with per-customer limits/usage tracking if needed.
        return $discount->is_active && $discount->isWithinDateRange();
    }

    public function applyAutoDiscounts(Customer $customer): void
    {
        // Placeholder: attach auto discounts to customer via pivot if rules match.
    }
}
