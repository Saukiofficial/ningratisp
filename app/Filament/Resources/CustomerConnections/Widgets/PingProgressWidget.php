<?php

namespace App\Filament\Resources\CustomerConnections\Widgets;

use App\Filament\Resources\CustomerConnections\CustomerConnectionResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class PingProgressWidget extends BaseWidget
{
    protected ?string $pollingInterval = '2s';

    protected function getStats(): array
    {
        $total = Cache::get('ping_all_customers_total', 0);
        $processed = Cache::get('ping_all_customers_processed', 0);
        $online = Cache::get('ping_all_customers_online', 0);
        $offline = Cache::get('ping_all_customers_offline', 0);

        $pending = $total - $processed;

        return [
            Stat::make('Pending', $pending)
                ->description('Customers waiting to be pinged')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
            Stat::make('Processed', $processed . '/' . $total)
                ->description('Customers that have been pinged')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info')
                ->url(CustomerConnectionResource::getUrl('index', ['filters[status][value]' => ''])),
            Stat::make('Online', $online)
                ->description('Customers that are online')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(CustomerConnectionResource::getUrl('index', ['filters[status][value]' => 'online'])),
            Stat::make('Offline', $offline)
                ->description('Customers that are offline')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->url(CustomerConnectionResource::getUrl('index', ['filters[status][value]' => 'offline'])),
        ];
    }
}
