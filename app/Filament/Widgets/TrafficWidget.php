<?php

namespace App\Filament\Widgets;

use App\Helpers\MikrotikAPI;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TrafficWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        try {

            if (!$this->isMikrotikReachable()) {
                throw new \Exception('');
            }

            $mikrotik = new MikrotikAPI();
            $interfaces = ['ether11', 'ether12'];

            $totalTx = 0;
            $totalRx = 0;

            foreach ($interfaces as $interface) {
                $trafficData = $mikrotik->getInterfaceTraffic($interface);

                if (is_array($trafficData) && isset($trafficData[0])) {
                    $totalTx += $trafficData[0]['tx-bits-per-second'] ?? 0;
                    $totalRx += $trafficData[0]['rx-bits-per-second'] ?? 0;
                }
            }

            // Convert to Mbps
            $txMbps = $totalTx / 1_000_000;
            $rxMbps = $totalRx / 1_000_000;

            // Cache the successful results
            $trafficData = [
                'tx_mbps' => $txMbps,
                'rx_mbps' => $rxMbps,
                'timestamp' => now()
            ];
            cache()->put('interface_traffic_data', $trafficData, now()->addMinutes(2));
            cache()->put('traffic_widget_status', 'online', now()->addMinutes(2));

            return [
                Stat::make('Upload', number_format($txMbps, 2) . ' Mbps')
                    ->description('Combined upload (ether11 + ether12)')
                    ->color('info')
                    ->icon('heroicon-o-arrow-up'),
                Stat::make('Download', number_format($rxMbps, 2) . ' Mbps')
                    ->description('Combined download (ether11 + ether12)')
                    ->color('info')
                    ->icon('heroicon-o-arrow-down'),
            ];
        } catch (\Exception $e) {

            // Get cached traffic data
            $cachedData = cache()->get('interface_traffic_data', [
                'tx_mbps' => 0,
                'rx_mbps' => 0,
                'timestamp' => now()->subHours(1)
            ]);

            $cacheAge = $this->getCacheAge($cachedData['timestamp']);
            $offlineDescription = $this->getOfflineDescription($cacheAge);

            return [
                Stat::make('Upload', number_format($cachedData['tx_mbps'], 2) . ' Mbps')
                    ->description($offlineDescription)
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle'),
                Stat::make('Download', number_format($cachedData['rx_mbps'], 2) . ' Mbps')
                    ->description($offlineDescription)
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle'),
            ];
        }
    }

    /**
     * Get the age of cached data in a human-readable format
     */
    private function getCacheAge($timestamp): string
    {
        if (!$timestamp) {
            return 'unknown time';
        }

        return $timestamp->diffForHumans();
    }

    /**
     * Generate appropriate description for offline state
     */
    private function getOfflineDescription(string $cacheAge): string
    {
        return "Mikrotik offline - Last data: {$cacheAge}";
    }

    /**
     * Check if Mikrotik is reachable before making API calls
     */
    private function isMikrotikReachable(): bool
    {
        try {
            $mikrotik = new MikrotikAPI();
            // Basic connection test - adjust based on your MikrotikAPI implementation
            return $mikrotik->isConnected() ?? true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Enhanced version with connection pre-check and graceful degradation
     */
    protected function getStatsWithPreCheck(): array
    {
        // Check connection first
        if (!$this->isMikrotikReachable()) {
            $cachedData = cache()->get('interface_traffic_data', [
                'tx_mbps' => 0,
                'rx_mbps' => 0,
                'timestamp' => now()->subHours(1)
            ]);

            $cacheAge = $this->getCacheAge($cachedData['timestamp']);

            return [
                Stat::make('Upload', number_format($cachedData['tx_mbps'], 2) . ' Mbps')
                    ->description("Connection failed - Last: {$cacheAge}")
                    ->color('warning')
                    ->icon('heroicon-o-wifi-x'),
                Stat::make('Download', number_format($cachedData['rx_mbps'], 2) . ' Mbps')
                    ->description("Connection failed - Last: {$cacheAge}")
                    ->color('warning')
                    ->icon('heroicon-o-wifi-x'),
            ];
        }

        return $this->getStats();
    }

    /**
     * Get traffic data with partial failure handling
     * Useful if only some interfaces fail
     */
    protected function getStatsWithPartialFailure(): array
    {
        $mikrotik = new MikrotikAPI();
        $interfaces = ['ether11', 'ether12'];

        $totalTx = 0;
        $totalRx = 0;
        $failedInterfaces = [];
        $successfulInterfaces = [];

        foreach ($interfaces as $interface) {
            try {
                $trafficData = $mikrotik->getInterfaceTraffic($interface);

                if (is_array($trafficData) && isset($trafficData[0])) {
                    $totalTx += $trafficData[0]['tx-bits-per-second'] ?? 0;
                    $totalRx += $trafficData[0]['rx-bits-per-second'] ?? 0;
                    $successfulInterfaces[] = $interface;
                } else {
                    $failedInterfaces[] = $interface;
                }
            } catch (\Exception $e) {
                $failedInterfaces[] = $interface;
                \Log::warning("Failed to get traffic data for {$interface}: " . $e->getMessage());
            }
        }

        // Convert to Mbps
        $txMbps = $totalTx / 1_000_000;
        $rxMbps = $totalRx / 1_000_000;

        // Determine status and description
        $color = empty($failedInterfaces) ? 'info' : 'warning';
        $description = $this->buildDescription($successfulInterfaces, $failedInterfaces);

        // Cache partial or full results
        if (!empty($successfulInterfaces)) {
            $trafficData = [
                'tx_mbps' => $txMbps,
                'rx_mbps' => $rxMbps,
                'timestamp' => now(),
                'successful_interfaces' => $successfulInterfaces,
                'failed_interfaces' => $failedInterfaces
            ];
            cache()->put('interface_traffic_data', $trafficData, now()->addMinutes(2));
        }

        return [
            Stat::make('Upload', number_format($txMbps, 2) . ' Mbps')
                ->description($description)
                ->color($color)
                ->icon($color === 'info' ? 'heroicon-o-arrow-up' : 'heroicon-o-exclamation-triangle'),
            Stat::make('Download', number_format($rxMbps, 2) . ' Mbps')
                ->description($description)
                ->color($color)
                ->icon($color === 'info' ? 'heroicon-o-arrow-down' : 'heroicon-o-exclamation-triangle'),
        ];
    }

    /**
     * Build description based on interface status
     */
    private function buildDescription(array $successful, array $failed): string
    {
        if (empty($failed)) {
            return 'Combined traffic (' . implode(' + ', $successful) . ')';
        }

        if (empty($successful)) {
            return 'All interfaces offline - Using cached data';
        }

        return 'Partial data (' . implode(' + ', $successful) . ') - ' . implode(', ', $failed) . ' offline';
    }
}
