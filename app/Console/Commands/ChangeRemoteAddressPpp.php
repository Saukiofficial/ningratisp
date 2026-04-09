<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ChangeRemoteAddressPpp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-remote-address-ppp 
                            {--dry-run : Only show what would be changed}
                            {--area= : Filter by area customer_prefix or name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate customer remote addresses to new remote_prefix segments (e.g. 192.168.20.102 -> 192.168.21.2)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $areaFilter = $this->option('area');
        $mikrotik = new \App\Helpers\MikrotikAPINative;

        $allAreas = \App\Models\PppArea::all();

        if ($allAreas->isEmpty()) {
            $this->error('No areas found in database.');
            return 1;
        }

        $selectedAreas = collect();

        if ($areaFilter) {
            $selectedAreas = \App\Models\PppArea::query()
                ->where('customer_prefix', $areaFilter)
                ->orWhere('name', 'like', "%{$areaFilter}%")
                ->get();

            if ($selectedAreas->isEmpty()) {
                $this->error("No areas found matching \"{$areaFilter}\"");
                return 1;
            }
        } else {
            $choices = $allAreas->pluck('name')->toArray();
            array_unshift($choices, 'All Areas');
            $choices[] = 'Cancel';

            $selection = $this->choice(
                'Which area would you like to migrate?',
                $choices,
                0 // Default to 'All Areas'
            );

            if ($selection === 'Cancel') {
                $this->info('Operation cancelled.');
                return 0;
            }

            if ($selection === 'All Areas') {
                $selectedAreas = $allAreas;
            } else {
                $selectedAreas = $allAreas->where('name', $selection);
            }
        }

        $this->info('Fetching secrets from Mikrotik...');
        $secrets_raw = $mikrotik->getPppSecrets(false);

        if (isset($secrets_raw['error'])) {
            $this->error('Failed to connect to Mikrotik: ' . ($secrets_raw['message'] ?? 'Unknown error'));
            return 1;
        }

        $secrets = collect($secrets_raw);
        $count = 0;

        foreach ($allAreas as $area) {
            $networkPrefix = $area->network_prefix;
            $remotePrefix = $area->remote_prefix;

            if (empty($remotePrefix) || $networkPrefix === $remotePrefix) {
                continue;
            }

            $this->comment("Processing Area: {$area->name} ({$networkPrefix} -> {$remotePrefix})");

            $customers = \App\Models\Customer::query()
                ->where('remote_address', 'like', $networkPrefix . '%')
                ->get();

            foreach ($customers as $customer) {
                $oldRemoteIp = $customer->remote_address;
                $suffixStr = str_replace($networkPrefix, '', $oldRemoteIp);

                if (!is_numeric($suffixStr)) {
                    continue;
                }

                $suffix = (int) $suffixStr;

                if ($suffix > 100) {
                    $newSuffix = $suffix - 100;
                    $newRemoteIp = $remotePrefix . $newSuffix;

                    $this->line("Migrating <info>{$customer->username}</info>: {$oldRemoteIp} -> <comment>{$newRemoteIp}</comment>");

                    if ($dryRun) {
                        $count++;
                        continue;
                    }

                    // Update Mikrotik
                    $mikrotikSecret = $secrets->firstWhere('name', $customer->username);
                    if ($mikrotikSecret) {
                        $response = $mikrotik->request('/ppp/secret/set', [
                            '.id' => $mikrotikSecret['.id'],
                            'remote-address' => $newRemoteIp,
                        ]);

                        if (isset($response['error']) && $response['error'] && $response['message'] != 'Empty Response') {
                            $this->error("  [Mikrotik] Gagal: " . ($response['message'] ?? 'Unknown Error'));
                            continue;
                        }
                    } else {
                        $this->warn("  [Mikrotik] Secret tidak ditemukan");
                    }

                    // Update DB
                    $customer->update(['remote_address' => $newRemoteIp]);
                    $count++;
                }
            }
        }

        $this->info("Done! Processed {$count} records.");
        return 0;
    }
}
