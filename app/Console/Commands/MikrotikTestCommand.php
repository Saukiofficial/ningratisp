<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPI;
use Illuminate\Console\Command;

class MikrotikTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mikrotik:test {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Mikrotik API helper';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $mikrotik = new MikrotikAPI();

        $this->info("Testing action: {$action}");

        $response = null;
        switch ($action) {
            case 'test':
                $response = $mikrotik->test();
                break;
            case 'system-resource':
                $response = $mikrotik->getSystemResource();
                break;
            case 'system-routerboard':
                $response = $mikrotik->getSystemRouterboard();
                break;
            case 'ip-addresses':
                $response = $mikrotik->getIpAddresses();
                break;
            case 'ip-routes':
                $response = $mikrotik->getIpRoutes();
                break;
            case 'ip-dns':
                $response = $mikrotik->getIpDns();
                break;
            case 'ppp-secrets':
                $response = $mikrotik->getPppSecrets();
                break;
            case 'ppp-active':
                $response = $mikrotik->getPppActive();
                break;
            case 'ppp-profiles':
                $response = $mikrotik->getPppProfiles();
                break;
            case 'hotspot-users':
                $response = $mikrotik->getHotspotUsers();
                break;
            case 'hotspot-active':
                $response = $mikrotik->getHotspotActive();
                break;
            case 'hotspot-profiles':
                $response = $mikrotik->getHotspotProfiles();
                break;
            case 'hotspot-servers':
                $response = $mikrotik->getHotspotServers();
                break;
            default:
                $this->error("Invalid action: {$action}");
                $this->info('Available actions: test, system-resource, system-routerboard, ip-addresses, ip-routes, ip-dns, ppp-secrets, ppp-active, ppp-profiles, hotspot-users, hotspot-active, hotspot-profiles, hotspot-servers');
                return 1;
        }

        if (is_array($response)) {
            if (isset($response['error'])) {
                $this->error(json_encode($response, JSON_PRETTY_PRINT));
            } else {
                // If it's a list of items, display as a table
                if (count($response) > 0 && isset($response[0]) && is_array($response[0])) {
                    $headers = array_keys($response[0]);
                    $this->table($headers, $response);
                } else {
                    $this->info(json_encode($response, JSON_PRETTY_PRINT));
                }
            }
        } else {
            $this->line((string)$response);
        }

        return 0;
    }
}
