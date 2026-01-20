<?php

namespace App\Services;

use App\Models\AccountReceivable;
use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\CustomerPackages;
use App\Models\Invoices;
use App\Models\Packages;
use Illuminate\Support\Carbon;

class CustomerService
{
    public function classifyCustomerCategory(Customer $customer): string
    {
        // Placeholder: derive category based on rules; keep existing
        return $customer->customer_category ?? 'normal';
    }

    public function grantCredit(Customer $customer, float $amount, string $referenceId): CustomerCredit
    {
        // Placeholder: create credit entry
        return new CustomerCredit();
    }

    public function chargeReceivable(Customer $customer, float $amount, ?Invoices $invoice = null): AccountReceivable
    {
        // Placeholder: create AR entry linked to invoice if provided
        return new AccountReceivable();
    }

    public function createCustomerPackage(Customer $customer, Packages $package, Carbon $startDate, bool $autoRenew = true): CustomerPackages
    {
        // Placeholder: create customer package record
        return new CustomerPackages();
    }

    public function renewPackagesForDate(Carbon $date): int
    {
        // Placeholder: renew active packages due for renewal
        return 0;
    }
}
