<?php

namespace App\Filament\Widgets;

use App\Filament\Trait\RefreshDashboardWidget;
use App\Models\Payment;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PaymentsByMethodChart extends ApexChartWidget
{
    use RefreshDashboardWidget;

    protected static ?string $chartId = 'paymentsByMethodChart';
    protected static ?string $heading = 'Payments by Method (Last 30 Days)';
    protected int | string | array $columnSpan = 1;
    protected static bool $deferLoading = true;

    protected function getOptions(): array
    {
        return Cache::remember(self::class, now()->addHour(), function () {
            $data = Payment::query()
                ->join('payment_methods', 'payments.payment_method_id', '=', 'payment_methods.id')
                ->where('payments.created_at', '>=', now()->subDays(30))
                ->groupBy('payment_methods.name')
                ->select('payment_methods.name', DB::raw('sum(payments.total_amount) as total'))
                ->get();

            return [
                'chart' => [
                    'type' => 'donut',
                    'height' => 300,
                ],
                'series' => $data->map(fn($item) => $item->total),
                'labels' => $data->map(fn($item) => $item->name),
                'legend' => [
                    'position' => 'bottom',
                ]
            ];
        });
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
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
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opt) {
                    let series = opt.w.config.series[opt.seriesIndex];
                    if (series >= 1000000) { return (series / 1000000).toFixed(1).replace(/\.0$/, '') + 'Jt'; }
                    if (series >= 1000) { return (series / 1000).toFixed(1).replace(/\.0$/, '') + 'Rb'; }
                    return series;
                }
            }
        }
        JS);
    }
}
