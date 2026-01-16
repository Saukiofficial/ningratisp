<?php

namespace App\Filament\Resources\Customers\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStats extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $stats = Customer::query()
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN isolir_at IS NULL THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN isolir_at IS NOT NULL THEN 1 ELSE 0 END) as isolir
        ')
            ->first();

        return [
            Stat::make('Total Customers', $stats->total)
                ->color('gray')
                ->description('Total saved customers'),

            Stat::make('Active', $stats->active)
                ->description('Not isolir')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Isolir', $stats->isolir)
                ->description('Isolated customers')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('danger'),
        ];
    }
}
