<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\PppProfile;
use Illuminate\Console\Command;

use function Symfony\Component\Clock\now;

class SyncCustomerPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-customer-package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ppps = PppProfile::with('package')->whereHas('package')->get()
            ->pluck('package.id', 'id');

        $customers = Customer::query()->get(['id', 'ppp_profile_id', 'username']);
        foreach ($customers as $customer) {
            if (!isset($ppps[$customer->ppp_profile_id])) {
                $this->info("{$customer->username} : {$customer->ppp_profile_id}");
                $this->newLine();
                continue;
            }

            $customer->customerPackages()->updateOrCreate(
                [
                    'package_id' => $ppps[$customer->ppp_profile_id],
                ],
                [
                    'start_date' => now()
                ]
            );
        }
    }
}
