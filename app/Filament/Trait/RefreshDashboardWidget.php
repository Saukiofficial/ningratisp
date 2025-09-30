<?php

namespace App\Filament\Trait;

use Illuminate\Support\Facades\Cache;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;

trait RefreshDashboardWidget
{
    #[On('refresh-dashboard')]
    public function refreshWidgetData(): void
    {
        Cache::forget(self::class);
        if ($this instanceof ApexChartWidget) {
            $this->updateOptions();
        }
    }

    protected function getDeferLoading(): ?bool
    {
        return true;
    }
}
