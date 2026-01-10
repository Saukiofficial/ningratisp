<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPI;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckActivePPP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-active-ppp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check active PPP connections and export inactive users to a JSON file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for inactive PPP users...');

        $customers = Customer::query()->get(['username',  'remote_address']);
        $pppActive = (new MikrotikAPI)->getPppActive();

        if (isset($pppActive['error'])) {
            $this->error('Failed to get active PPP list from MikroTik: '.$pppActive['message']);

            return 1;
        }

        $activeUsernames = array_map(function ($ppp) {
            return $ppp['name'];
        }, $pppActive);

        $inactiveCustomers = $customers->filter(function ($customer) use ($activeUsernames) {
            return ! in_array($customer->username, $activeUsernames);
        });

        $fileName = 'inactive_ppp_users.json';
        Storage::put($fileName, json_encode(array_values($inactiveCustomers->toArray()), JSON_PRETTY_PRINT));

        $this->info('Successfully exported '.$inactiveCustomers->count().' inactive PPP users to '.storage_path('app/'.$fileName));

        return 0;
    }
}
