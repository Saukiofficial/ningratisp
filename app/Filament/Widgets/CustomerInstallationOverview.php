<?php

namespace App\Filament\Widgets;

use App\Models\CustomerInstallationOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerInstallationOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Installations', CustomerInstallationOrder::where('status', CustomerInstallationOrder::STATUS_PENDING)->count())
                ->description('Orders waiting for processing')
                ->color('gray'),
            Stat::make('On Progress', CustomerInstallationOrder::where('status', CustomerInstallationOrder::STATUS_ON_PROGRESS)->count())
                ->description('Installations currently being worked on')
                ->color('warning'),
            Stat::make('Done Installations', CustomerInstallationOrder::where('status', CustomerInstallationOrder::STATUS_DONE)->count())
                ->description('Completed installations')
                ->color('success'),
            Stat::make('Cancelled', CustomerInstallationOrder::where('status', CustomerInstallationOrder::STATUS_CANCELLED)->count())
                ->description('Cancelled or rescheduled orders')
                ->color('danger'),
        ];
    }
}
