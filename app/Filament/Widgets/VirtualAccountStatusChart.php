<?php

namespace App\Filament\Widgets;

use App\Filament\Trait\RefreshDashboardWidget;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class VirtualAccountStatusChart extends ApexChartWidget
{
    use RefreshDashboardWidget;

    protected static ?string $chartId = 'virtualAccountStatusChart';
    protected static ?string $heading = 'Virtual Account Status';
    protected int | string | array $columnSpan = 1;

    protected function getOptions(): array
    {
        return Cache::remember(self::class, now()->addHour(), function () {
            $data = VirtualAccount::query()
                ->groupBy('status')
                ->select('status', DB::raw('count(*) as total'))
                ->get();

            return [
                'chart' => [
                    'type' => 'donut',
                    'height' => 300,
                ],
                'series' => $data->map(fn($item) => $item->total),
                'labels' => $data->map(fn($item) => $item->status),
                'legend' => [
                    'position' => 'bottom',
                ]
            ];
        });
    }
}
