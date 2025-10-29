<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class PingAllCustomersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public ?int $limit = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = Customer::whereNotNull('remote_address')->limit($this->limit)->get();
        $totalCustomers = $customers->count();

        Cache::put('ping_all_customers_total', $totalCustomers);
        Cache::put('ping_all_customers_processed', 0);
        Cache::put('ping_all_customers_online', 0);
        Cache::put('ping_all_customers_offline', 0);

        foreach ($customers as $customer) {
            PingCustomerJob::dispatch($customer);
        }

        Cache::put('ping_all_customers_finished_at', now());
        Notification::make()
            ->title('Ping All Customers Completed')
            ->success()
            ->sendToDatabase($this->user);
    }
}
