<?php

namespace App\Jobs;

use App\Helpers\MikrotikAPI;
use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ActivateCustomerInternetJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Customer $customer,
        public array $data = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(MikrotikAPI::class)->isolirClient(
            $this->customer->username,
            false
        );
    }
}
