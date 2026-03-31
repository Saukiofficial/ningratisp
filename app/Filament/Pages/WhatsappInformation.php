<?php

namespace App\Filament\Pages;

use App\Helpers\WahaApi;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Filament\Widgets\WhatsappStatusWidget;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Width;

class WhatsappInformation extends Page
{
    use HasPageShield;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-information-circle';

    protected string $view = 'filament.pages.whatsapp-information';

    public ?string $status = null;
    public ?string $qrCode = null;
    public ?array $me = null;
    public ?array $sessions = null;
    public ?string $screenshot = null;

    public function mount(): void
    {
        $this->refreshStatus();
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WhatsappStatusWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Status')
                ->action('refreshStatus'),

            Action::make('disconnect')
                ->label('Disconnect')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn() => $this->status === 'WORKING')
                ->action('disconnect'),
        ];
    }

    public function refreshStatus(): void
    {
        $wahaApi = new WahaApi();
        $status = $wahaApi->getSessionStatus();

        $this->status = $status['status'] ?? 'UNKNOWN';
        $this->qrCode = null;
        $this->me = null;
        $this->sessions = null;
        $this->screenshot = null;

        if ($this->status === 'SCAN_QR_CODE') {
            $qrCode = $wahaApi->getQrCode();
            $this->qrCode = $qrCode ?? null;
        } elseif ($this->status === 'WORKING') {
            $this->me = $wahaApi->getMe();
            $this->sessions = $wahaApi->getSessions();
            $this->screenshot = $wahaApi->getScreenshot();
        }
    }

    public function disconnect(): void
    {
        try {
            $wahaApi = new WahaApi();
            $wahaApi->logout();

            Notification::make()
                ->title('Successfully disconnected')
                ->success()
                ->send();

            $this->refreshStatus();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to disconnect')
                ->danger()
                ->send();
        }
    }
}
