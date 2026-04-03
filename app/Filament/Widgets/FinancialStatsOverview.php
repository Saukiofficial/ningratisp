<?php

namespace App\Filament\Widgets;

use App\Filament\Trait\RefreshDashboardWidget;
use App\Helpers\NumberFormatter;
use App\Models\Invoices;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class FinancialStatsOverview extends BaseWidget
{
    use RefreshDashboardWidget, HasWidgetShield;

    protected function getStats(): array
    {
        return Cache::remember(self::class, now()->addHour(), function () {
            $currentMonthInvoices = Invoices::query()->whereMonth('invoice_date', now()->month)
                ->whereYear('invoice_date', now()->year);

            $grossRevenue = (clone $currentMonthInvoices)->sum('subtotal');
            $totalDiscounts = (clone $currentMonthInvoices)->sum('discount_amount');
            $netRevenue = (clone $currentMonthInvoices)->where('status', Invoices::STATUS_PAID)->sum('total_amount');

            return [
                Stat::make('Gross Revenue (This Month)', NumberFormatter::humanReadable($grossRevenue, 'Rp'))
                    ->description('Revenue before discounts')
                    ->color('success'),
                Stat::make('Total Discounts (This Month)', NumberFormatter::humanReadable($totalDiscounts, 'Rp'))
                    ->description('Total value of all discounts applied')
                    ->color('warning'),
                Stat::make('Net Revenue (This Month)', NumberFormatter::humanReadable($netRevenue, 'Rp'))
                    ->description('Gross Revenue - Discounts')
                    ->color('info'),
            ];
        });
    }
}
