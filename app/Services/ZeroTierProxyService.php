<?php

namespace App\Services;

use App\Models\RouterProxy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ZeroTierProxyService
{
    protected $managerHost;
    protected $managerPort;
    protected $timeout;

    public function __construct()
    {
        $this->managerHost = config('zerotier.manager_host', 'localhost');
        $this->managerPort = config('zerotier.manager_port', 9999);
        $this->timeout = config('zerotier.timeout', 10);
    }

    public function createOrGetProxy(int $customerId, string $routerIp, int $routerPort = 80): ?RouterProxy
    {
        // Check if proxy already exists
        $proxy = RouterProxy::where('customer_id', $customerId)
            ->where('router_ip', $routerIp)
            ->where('is_active', true)
            ->first();

        if ($proxy) {
            Log::info("Using existing proxy for customer {$customerId}: {$proxy->router_id}");
            $proxy->update(['last_accessed_at' => now()]);
            $this->updateLastUsed($proxy->router_id);
            return $proxy;
        }

        // Test if manager is reachable
        if (!$this->testConnection()) {
            Log::error("Cannot connect to ZeroTier manager at {$this->managerHost}:{$this->managerPort}");
            return null;
        }

        // Generate unique router ID
        $routerId = $this->generateRouterId($customerId);

        Log::info("Creating new proxy for customer {$customerId}: {$routerId} -> {$routerIp}:{$routerPort}");

        // Create nginx upstream
        $result = $this->addNginxUpstream($routerId, $routerIp, $routerPort);

        if (!$result) {
            Log::error("Failed to create nginx upstream for router {$routerId}", [
                'customer_id' => $customerId,
                'router_ip' => $routerIp,
                'router_port' => $routerPort,
                'manager_host' => $this->managerHost,
                'manager_port' => $this->managerPort
            ]);
            return null;
        }

        // Save to database
        $proxy = RouterProxy::create([
            'customer_id' => $customerId,
            'router_id' => $routerId,
            'router_ip' => $routerIp,
            'router_port' => $routerPort,
            'is_active' => true,
            'last_accessed_at' => now()
        ]);

        Log::info("Successfully created proxy for customer {$customerId}: {$routerId}");

        return $proxy;
    }

    public function removeProxy(RouterProxy $proxy): bool
    {
        $result = $this->removeNginxUpstream($proxy->router_id);

        if ($result) {
            $proxy->update(['is_active' => false]);
            Log::info("Removed proxy: {$proxy->router_id}");
        }

        return $result;
    }

    protected function generateRouterId(int $customerId): string
    {
        return 'customer_' . $customerId . '_' . Str::random(8);
    }

    protected function addNginxUpstream(string $routerId, string $routerIp, int $routerPort): bool
    {
        return $this->sendCommand("ADD {$routerId} {$routerIp} {$routerPort}");
    }

    protected function removeNginxUpstream(string $routerId): bool
    {
        return $this->sendCommand("REMOVE {$routerId}");
    }

    protected function updateLastUsed(string $routerId): bool
    {
        return $this->sendCommand("UPDATE_USED {$routerId}");
    }

    public function testConnection(): bool
    {
        $response = $this->sendCommand("PING", true);
        return str_contains($response, 'PONG');
    }

    public function listRouters(): array
    {
        $response = $this->sendCommand("LIST", true);

        if (!$response) {
            return [];
        }

        $lines = explode("\n", trim($response));
        $routers = [];

        foreach ($lines as $line) {
            if (preg_match('/^(.+): (.+):(\d+)$/', $line, $matches)) {
                $routers[] = [
                    'id' => $matches[1],
                    'ip' => $matches[2],
                    'port' => (int)$matches[3]
                ];
            }
        }

        return $routers;
    }

    public function reloadNginx(): bool
    {
        return $this->sendCommand("RELOAD");
    }

    protected function sendCommand(string $command, bool $returnResponse = false): bool|string
    {
        try {
            Log::debug("Sending command to manager: {$command}");

            // Try socket connection with explicit error handling
            $errno = 0;
            $errstr = '';

            $socket = @fsockopen(
                $this->managerHost,
                $this->managerPort,
                $errno,
                $errstr,
                $this->timeout
            );

            if (!$socket) {
                Log::error("Cannot connect to ZeroTier manager", [
                    'host' => $this->managerHost,
                    'port' => $this->managerPort,
                    'errno' => $errno,
                    'error' => $errstr
                ]);
                return $returnResponse ? '' : false;
            }

            // Set timeout for socket operations
            stream_set_timeout($socket, $this->timeout);

            // Send command
            $written = fwrite($socket, $command . "\n");
            if ($written === false) {
                Log::error("Failed to write command to socket");
                fclose($socket);
                return $returnResponse ? '' : false;
            }

            // Read response
            $response = '';
            $startTime = time();

            while (!feof($socket)) {
                $line = fgets($socket, 4096);
                if ($line === false) {
                    break;
                }
                $response .= $line;

                // Timeout check
                if (time() - $startTime > $this->timeout) {
                    Log::warning("Socket read timeout");
                    break;
                }

                // If we got a complete response, break
                if (str_starts_with($line, 'OK:') || str_starts_with($line, 'ERROR:')) {
                    break;
                }
            }

            fclose($socket);

            $response = trim($response);
            Log::debug("Received response from manager", ['response' => $response]);

            if ($returnResponse) {
                return $response;
            }

            return str_starts_with($response, 'OK');
        } catch (\Exception $e) {
            Log::error("Exception sending command to ZeroTier manager", [
                'command' => $command,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $returnResponse ? '' : false;
        }
    }

    public function getProxyUrl(RouterProxy $proxy, string $path = ''): string
    {
        $baseUrl = config('zerotier.proxy_url', 'http://localhost');
        return rtrim($baseUrl, '/') . '/router/' . $proxy->router_id . '/' . ltrim($path, '/');
    }

    public function getManagerStatus(): array
    {
        $isOnline = $this->testConnection();

        return [
            'online' => $isOnline,
            'host' => $this->managerHost,
            'port' => $this->managerPort,
            'active_routers' => $isOnline ? count($this->listRouters()) : 0
        ];
    }
}
