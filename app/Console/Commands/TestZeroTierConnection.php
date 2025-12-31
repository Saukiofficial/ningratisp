<?php

namespace App\Console\Commands;

use App\Services\ZeroTierProxyService;
use Illuminate\Console\Command;

class TestZeroTierConnection extends Command
{
    protected $signature = 'zerotier:test';
    protected $description = 'Test ZeroTier manager connection';

    public function handle(ZeroTierProxyService $service)
    {
        $this->info('Testing ZeroTier Manager Connection...');
        $this->newLine();

        $status = $service->getManagerStatus();

        $this->table(
            ['Property', 'Value'],
            [
                ['Host', $status['host']],
                ['Port', $status['port']],
                ['Status', $status['online'] ? '✓ Online' : '✗ Offline'],
                ['Active Routers', $status['active_routers']],
            ]
        );

        if ($status['online']) {
            $this->newLine();
            $this->info('Listing active routers:');
            $routers = $service->listRouters();

            if (empty($routers)) {
                $this->warn('No active routers');
            } else {
                $this->table(
                    ['Router ID', 'IP', 'Port'],
                    array_map(fn($r) => [$r['id'], $r['ip'], $r['port']], $routers)
                );
            }

            return Command::SUCCESS;
        }

        $this->error('Cannot connect to ZeroTier manager!');
        $this->newLine();
        $this->warn('Troubleshooting steps:');
        $this->line('1. Check if docker container is running: docker ps | grep zerotier');
        $this->line('2. Check container logs: docker logs zerotier-proxy');
        $this->line('3. Check manager logs: docker exec zerotier-proxy cat /var/log/nginx/manager.log');
        $this->line('4. Test from inside container: docker exec zerotier-proxy sh -c "echo PING | socat - TCP:localhost:9998"');

        return Command::FAILURE;
    }
}
