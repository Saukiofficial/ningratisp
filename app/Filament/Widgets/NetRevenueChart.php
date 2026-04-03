<?php

namespace App\Filament\Widgets;

use App\Filament\Trait\RefreshDashboardWidget;
use App\Models\Invoices;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\RawJs;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Cache;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class NetRevenueChart extends ApexChartWidget
{
    use RefreshDashboardWidget, HasWidgetShield;

    protected static ?string $chartId = 'netRevenueChart';
    protected static ?string $heading = 'Net Revenue (Last 12 Months)';
    protected static bool $deferLoading = true;

    protected function getOptions(): array
    {
        return Cache::remember(self::class, now()->addHour(), function () {
            $data = Trend::query(
                Invoices::query()->where('status', Invoices::STATUS_PAID)

            )
                ->dateColumn('invoice_date')
                ->between(
                    start: now()->subYear(),
                    end: now(),
                )
                ->perMonth()
                ->sum('total_amount');

            return [
                'chart' => [
                    'type' => 'line',
                    'height' => 300,
                ],
                'series' => [
                    [
                        'name' => 'Net Revenue',
                        'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    ],
                ],
                'xaxis' => [
                    'categories' => $data->map(fn(TrendValue $value) => $value->date),
                    'labels' => [
                        'style' => [
                            'colors' => '#9ca3af',
                            'fontWeight' => 600,
                        ],
                    ],
                ],
                'colors' => ['#6366f1'],
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
