<?php

namespace App\Filament\Widgets;

use App\Helpers\MikrotikAPINative;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class OnlineOfflineUsersStatWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isDiscovered = false;
    protected ?string $pollingInterval = '300s';

    protected function getStats(): array
    {
        try {

            if (!$this->isMikrotikReachable()) {
                throw new \Exception('');
            }

            $mikrotik = new MikrotikAPINative();
            $cacheUsers = $mikrotik->getPppSecrets();
            $activePpp = $mikrotik->getPppActive();

            $activeUsers = collect($activePpp)->pluck('name')->all();
            $onlineUsers = $offlineUsers = $expiredUsers = 0;
            $inactiveUsers = [];

            // online user
            if (is_array($activePpp) && (empty($activePpp) || isset($activePpp[0]))) {
                $onlineUsers = count($activePpp);
            }

            // find inactive user
            foreach ($cacheUsers as $user) {
                if (!isset($user['name']) || !isset($user['last-logged-out'])) {
                    continue;
                }

                if (!in_array($user['name'], $activeUsers)) {
                    $inactiveUsers[] = $user;
                }
            }

            // separete expired and offline user in inactive users
            array_map(
                function ($user) use (&$expiredUsers, &$offlineUsers) {
                    return Carbon::parse($user['last-logged-out'])->diffInMonths() > 1
                        ? $expiredUsers++ : $offlineUsers++;
                },
                $inactiveUsers
            );

            // Cache the successful result
            cache()->put('online_users_count', $onlineUsers, now()->addMinutes(5));
            cache()->put('offline_users_count', $offlineUsers, now()->addMinutes(5));
            cache()->put('expired_users_count', $expiredUsers, now()->addMinutes(5));
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
                Stat::make('Expired Users', $expiredUsers)
                    ->description('Expired PPPoE users')
                    ->color('danger')
                    ->icon(Heroicon::OutlinedCalendarDateRange),
            ];
        } catch (\Exception $e) {

            // Try to get cached data
            $cachedCount = cache()->get('online_users_count', 0);
            $cachedCountOffline = cache()->get('offline_users_count', 0);
            $cachedCountExpired = cache()->get('expired_users_count', 0);
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
                Stat::make('Expired Users', $cachedCountExpired)
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
        // This is a basic example - adjust based on your MikrotikAPINative class
        try {
            $mikrotik = new MikrotikAPINative();
            // Assuming your MikrotikAPINative has a simple connection test method
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
            $cachedExpiredCount = cache()->get('expired_users_count', 0);
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
                Stat::make('Expired Users', $cachedExpiredCount)
                    ->description("Connection failed - Last data: {$cacheAge}")
                    ->color('warning')
                    ->icon('heroicon-o-wifi-x'),
            ];
        }

        // Proceed with normal API call if connection is available
        return $this->getStats();
    }
}
