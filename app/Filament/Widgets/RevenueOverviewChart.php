<?php

namespace App\Filament\Widgets;

use App\Filament\Trait\RefreshDashboardWidget;
use App\Models\Invoices;
use Filament\Support\RawJs;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Cache;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RevenueOverviewChart extends ApexChartWidget
{
    use RefreshDashboardWidget;

    protected static ?string $chartId = 'revenueOverviewChart';
    protected static ?string $heading = 'Revenue Overview (Last 12 Months)';

    protected function getOptions(): array
    {
        return Cache::remember(self::class, now()->addHour(), function () {
            // Gross Revenue
            $grossData = Trend::query(
                Invoices::query()->where('status', Invoices::STATUS_PAID)
            )
                ->between(start: now()->subYear(), end: now())
                ->perMonth()
                ->sum('subtotal');

            // Discounts
            $discountData = Trend::query(
                Invoices::query()->where('status', Invoices::STATUS_PAID)

            )
                ->between(start: now()->subYear(), end: now())
                ->perMonth()
                ->sum('discount_amount');

            // Net Revenue
            $netData = Trend::query(
                Invoices::query()->where('status', Invoices::STATUS_PAID)

            )
                ->between(start: now()->subYear(), end: now())
                ->perMonth()
                ->sum('total_amount');

            return [
                'chart' => [
                    'type' => 'line',
                    'height' => 300,
                ],
                'series' => [
                    [
                        'name' => 'Gross Revenue',
                        'data' => $grossData->map(fn(TrendValue $value) => $value->aggregate),
                    ],
                    [
                        'name' => 'Total Discounts',
                        'data' => $discountData->map(fn(TrendValue $value) => $value->aggregate),
                    ],
                    [
                        'name' => 'Net Revenue',
                        'data' => $netData->map(fn(TrendValue $value) => $value->aggregate),
                    ],
                ],
                'xaxis' => [
                    'categories' => $netData->map(fn(TrendValue $value) => $value->date),
                    'labels' => [
                        'style' => ['colors' => '#9ca3af', 'fontWeight' => 600],
                    ],
                ],
                'colors' => ['#22c55e', '#f59e0b', '#3b82f6'],
                'stroke' => [
                    'curve' => 'smooth',
                ],
            ];
        });
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            yaxis: {
                labels: {
                    formatter: function(value) {
                        if (value >= 1000000) { return (value / 1000000).toFixed(1).replace(/\.0$/, '') + 'Jt'; }
                        if (value >= 1000) { return (value / 1000).toFixed(1).replace(/\.0$/, '') + 'Rb'; }
                        return value;
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(value);
                    }
                }
            }
        }
        JS);
    }
}
