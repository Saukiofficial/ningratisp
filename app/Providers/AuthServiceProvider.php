<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Customer\Invoices;
use App\Models\Customer\VirtualAccount;
use App\Policies\Customer\InvoicePolicy;
use App\Policies\Customer\VirtualAccountPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [

        // customer policy
        Invoices::class => InvoicePolicy::class,
        VirtualAccount::class => VirtualAccountPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
