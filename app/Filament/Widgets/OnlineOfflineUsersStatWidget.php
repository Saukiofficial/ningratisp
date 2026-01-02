<?php

namespace App\Filament\Widgets;

use App\Helpers\MikrotikAPI;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OnlineOfflineUsersStatWidget extends BaseWidget
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
            $activePpp = $mikrotik->getPppActive();
            $users = $mikrotik->getPppSecrets();
            $onlineUsers = 0;

            if (is_array($activePpp) && (empty($activePpp) || isset($activePpp[0]))) {
                $onlineUsers = count($activePpp);
            }
            $offlineUsers = count($users) - $onlineUsers;

            // Cache the successful result
            cache()->put('online_users_count', $onlineUsers, now()->addMinutes(5));
            cache()->put('offline_users_count', $offlineUsers, now()->addMinutes(5));
            cache()->put('mikrotik_status', 'online', now()->addMinutes(5));

            return [
                Stat::make('Online Users', $onlineUsers)
                    ->description('PPPoE users currently online')
                    ->color('success')
                    ->icon(Heroicon::OutlinedSignal),
                Stat::make('Offline Users', $offlineUsers)
                    ->description('PPPoE users currently offline')
                    ->color('danger')
                    ->icon(Heroicon::OutlinedSignalSlash),
            ];
        } catch (\Exception $e) {

            // Try to get cached data
            $cachedCount = cache()->get('online_users_count', 0);
            $cachedCountOffline = cache()->get('offline_users_count', 0);
            $lastKnownStatus = cache()->get('mikrotik_status', 'unknown');

            // Calculate how old the cached data is
            $cacheAge = $this->getCacheAge();

            return [
                Stat::make('Online Users', $cachedCount)
                    ->description($this->getOfflineDescription($cacheAge))
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle'),
                Stat::make('Offline Users', $cachedCountOffline)
                    ->description($this->getOfflineDescription($cacheAge))
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-triangle'),
            ];
        }
    }

    /**
     * Get the age of cached data in a human-readable format
     */
    private function getCacheAge(): string
    {
        $lastUpdate = cache()->get('online_users_last_update');

        if (!$lastUpdate) {
            cache()->put('online_users_last_update', now(), now()->addHours(24));
            return 'just now';
        }

        return $lastUpdate->diffForHumans();
    }

    /**
     * Generate appropriate description for offline state
     */
    private function getOfflineDescription(string $cacheAge): string
    {
        return "Mikrotik offline - Last data: {$cacheAge}";
    }

    /**
     * Alternative method: Check connection before making API calls
     */
    private function isMikrotikReachable(): bool
    {
        // You can implement a simple ping or connection test here
        // This is a basic example - adjust based on your MikrotikAPI class
        try {
            $mikrotik = new MikrotikAPI();
            // Assuming your MikrotikAPI has a simple connection test method
            // If not, you could do a basic socket connection test
            return $mikrotik->isConnected() ?? true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Enhanced version with connection pre-check
     */
    protected function getStatsWithPreCheck(): array
    {
        // Check if Mikrotik is reachable before attempting API calls
        if (!$this->isMikrotikReachable()) {
            $cachedCount = cache()->get('online_users_count', 0);
            $cachedOfflineCount = cache()->get('offline_users_count', 0);
            $cacheAge = $this->getCacheAge();

            return [
                Stat::make('Online Users', $cachedCount)
                    ->description("Connection failed - Last data: {$cacheAge}")
                    ->color('warning')
                    ->icon('heroicon-o-wifi-x'),
                Stat::make('Offline Users', $cachedOfflineCount)
                    ->description("Connection failed - Last data: {$cacheAge}")
                    ->color('warning')
                    ->icon('heroicon-o-wifi-x'),
            ];
        }

        // Proceed with normal API call if connection is available
        return $this->getStats();
    }
}
