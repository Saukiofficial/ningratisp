<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPI;
use App\Models\Customer;
use App\Models\CustomerPackages;
use App\Models\PppProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCustomerPppProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-customer-ppp-profile';

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
        $mikrotikUsers = (new MikrotikAPI)->getPppSecrets();
        $mikrotikUsers = array_column($mikrotikUsers, null, 'name');

        $pppProfiles = PppProfile::query()->pluck('id', 'profile_name');
        $ppps = PppProfile::with('package')->whereHas('package')->get()
            ->pluck('package.id', 'id');

        $customers = Customer::with('pppProfile')->get(['id', 'username', 'ppp_profile_id']);
        foreach ($customers as $customer) {

            if (!isset($mikrotikUsers[$customer->username]['profile'])) {
                $this->info('not found : ' . $customer->username);
                $this->newLine();
                continue;
            }

            if ($customer->pppProfile->profile_name != $mikrotikUsers[$customer->username]['profile']) {
                $this->info("$customer->username | old : {$customer->pppProfile->profile_name} | new : {$mikrotikUsers[$customer->username]['profile']}");
                $this->newLine();
                $newPppId = $pppProfiles[$mikrotikUsers[$customer->username]['profile']];

                DB::beginTransaction();

                $customer->update([
                    'ppp_profile_id' => $newPppId
                ]);
                $customer->save();

                $customer->customerPackages()->update([
                    'status' => CustomerPackages::STATUS_SUSPENDED
                ]);

                $customer->customerPackages()->create([
                    'package_id' => $ppps[$customer->ppp_profile_id],
                    'start_date' => now()
                ]);

                DB::commit();
            }
        }
    }
}
