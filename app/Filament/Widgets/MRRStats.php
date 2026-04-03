<?php

namespace App\Filament\Widgets;

use App\Filament\Trait\RefreshDashboardWidget;
use App\Helpers\NumberFormatter;
use App\Models\Invoices;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Illuminate\Support\Facades\Cache;

class MRRStats extends BaseWidget
{
    use RefreshDashboardWidget, HasWidgetShield;

    protected function getStats(): array
    {
        return Cache::remember(self::class, now()->addHour(), function () {
            $lastMonthNetRevenue = Trend::query(
                Invoices::query()->where('status', Invoices::STATUS_PAID)

            )
                ->between(
                    start: now()->subMonth()->startOfMonth(),
                    end: now()->subMonth()->endOfMonth(),
                )
                ->perMonth()
                ->sum('total_amount');

            $mrr = $lastMonthNetRevenue->first()?->aggregate ?? 0;
            $arr = $mrr * 12;

            return [
                Stat::make('Monthly Recurring Revenue (MRR)', NumberFormatter::humanReadable($mrr, 'Rp'))
                    ->description('Based on last month\'s net revenue')
                    ->color('success'),
                Stat::make('Annual Recurring Revenue (ARR)', NumberFormatter::humanReadable($arr, 'Rp'))
                    ->description('MRR x 12')
                    ->color('success'),
            ];
        });
    }
}
