<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\WhatsappSettingsPage;
use App\Helpers\WahaApi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WhatsappStatusWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    // protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $wahaApi = new WahaApi();
        $status = $wahaApi->getSessionStatus();
        $statusText = $status['status'] ?? 'UNKNOWN';

        $description = match ($statusText) {
            'WORKING' => 'WhatsApp API is connected and ready',
            'FAILED' => 'Connection failed. Try refreshing or reconnecting',
            'SCAN_QR_CODE' => 'Scan QR code to connect WhatsApp',
            default => 'Status unknown. Please refresh',
        };

        $color = match ($statusText) {
            'WORKING' => 'success',
            'FAILED' => 'danger',
            'SCAN_QR_CODE' => 'warning',
            default => 'gray',
        };

        $icon = match ($statusText) {
            'WORKING' => 'heroicon-m-check-circle',
            'FAILED' => 'heroicon-m-x-circle',
            'SCAN_QR_CODE' => 'heroicon-m-qr-code',
            default => 'heroicon-m-question-mark-circle',
        };

        return [
            Stat::make('WhatsApp Status', ucwords(strtolower(str_replace('_', ' ', $statusText))))
                ->description($description)
                ->descriptionIcon($icon)
                ->color($color)
                ->chart([1, 1, 1, 1, 1])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    // 'wire:click' => 'redirectToSettings',
                ]),
        ];
    }

    // public function redirectToSettings(): void
    // {
    //     $this->redirect(WhatsappSettingsPage::getUrl());
    // }
}
