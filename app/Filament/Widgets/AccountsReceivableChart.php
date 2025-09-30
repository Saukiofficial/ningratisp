<?php

namespace App\Filament\Widgets;

use App\Filament\Trait\RefreshDashboardWidget;
use App\Models\Invoices;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;

class AccountsReceivableChart extends ApexChartWidget
{
    use RefreshDashboardWidget;

    protected static ?string $chartId = 'accountsReceivableChart';
    protected static ?string $heading = 'Accounts Receivable Aging';
    protected int | string | array $columnSpan = 'full';
    protected static bool $deferLoading = true;

    protected function getOptions(): array
    {
        return Cache::remember(self::class, now()->addHour(), function () {
            // Prepare dates in PHP to use as bindings
            $now = now();
            $date30 = now()->subDays(30);
            $date31 = now()->subDays(31);
            $date60 = now()->subDays(60);
            $date61 = now()->subDays(61);
            $date90 = now()->subDays(90);

            // Use selectRaw with bindings to ensure database agnosticism
            $data = Invoices::query()
                ->where('status', Invoices::STATUS_UNPAID)
                ->selectRaw(
                    'SUM(CASE WHEN due_date >= ? THEN balance_due ELSE 0 END) as current, ' .
                        'SUM(CASE WHEN due_date BETWEEN ? AND ? THEN balance_due ELSE 0 END) as age_1_30, ' .
                        'SUM(CASE WHEN due_date BETWEEN ? AND ? THEN balance_due ELSE 0 END) as age_31_60, ' .
                        'SUM(CASE WHEN due_date BETWEEN ? AND ? THEN balance_due ELSE 0 END) as age_61_90, ' .
                        'SUM(CASE WHEN due_date < ? THEN balance_due ELSE 0 END) as age_90_plus',
                    [
                        $now,
                        $date30,
                        $now,
                        $date60,
                        $date31,
                        $date90,
                        $date61,
                        $date90
                    ]
                )
                ->first()
                ->toArray();

            // Map the database aliases to the desired display labels for the chart
            $labels = [
                'current' => 'Current',
                'age_1_30' => '1-30 Days',
                'age_31_60' => '31-60 Days',
                'age_61_90' => '61-90 Days',
                'age_90_plus' => '90+ Days',
            ];

            $chartData = [];
            $chartCategories = [];
            foreach ($labels as $key => $label) {
                $chartData[] = $data[$key] ?? 0;
                $chartCategories[] = $label;
            }

            return [
                'chart' => [
                    'type' => 'bar',
                    'height' => 300,
                ],
                'series' => [
                    [
                        'name' => 'AR Aging',
                        'data' => $chartData,
                    ],
                ],
                'xaxis' => [
                    'categories' => $chartCategories,
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
