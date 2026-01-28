<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncRemoteAddressCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-remote-address-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize customer remote address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Fetching customer data...');

        // 1. Fetch Customers
        $customers = Customer::query()
            ->whereNotNull('remote_address')
            ->get(['id', 'username', 'remote_address']);

        $total = $customers->count();

        if ($total === 0) {
            $this->components->warn('No customers with remote addresses found.');
            return;
        }

        $this->line("Found <comment>{$total}</comment> routers.");
        $this->newLine();

        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
        $bar->start();

        $customerMikrotik = (new MikrotikAPINative)->getPppSecrets();

        if (!empty($customerMikrotik['error'])) {
            $this->newLine();
            $this->components->error('Customer mikrotik empty or Connection problem');
            $this->info(json_encode($customerMikrotik));
            return;
        }
        $customerMikrotik = array_column($customerMikrotik, null, 'name');

        $updated = 0;
        foreach ($customers as $customer) {
            $bar->advance();

            if (isset($customerMikrotik[$customer->username]) && $customer->remote_address != $customerMikrotik[$customer->username]['remote-address']) {
                $customer->update([
                    'remote_address' => $customerMikrotik[$customer->username]['remote-address'],
                    'local_address' => $customerMikrotik[$customer->username]['local-address'],
                ]);
                $updated++;
            }

            // customer connection
            if (isset($customerMikrotik[$customer->username]) && $customer->customerConnection?->ip_address != $customerMikrotik[$customer->username]['remote-address']) {
                $customer->customerConnection()->update([
                    'ip_address' => $customerMikrotik[$customer->username]['remote-address']
                ]);
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Summary: <info>{$updated} Updated</info>");
    }
}
