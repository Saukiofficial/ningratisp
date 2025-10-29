<?php

namespace App\Filament\Resources\CustomerConnections\Pages;

use App\Filament\Resources\CustomerConnections\CustomerConnectionResource;
use App\Filament\Resources\CustomerConnections\Widgets\PingProgressWidget;
use App\Jobs\PingAllCustomersJob;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;

class ListCustomerConnections extends ListRecords
{
    protected static string $resource = CustomerConnectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ping_all')
                ->label('Run Ping')
                ->icon(Heroicon::OutlinedSignal)
                ->action(function (Action $action) {
                    Cache::put('ping_all_customers_total', 0);
                    Cache::put('ping_all_customers_processed', 0);
                    Cache::put('ping_all_customers_online', 0);
                    Cache::put('ping_all_customers_offline', 0);
                    Cache::put('ping_all_customers_started_at', now());
                    Cache::put('ping_all_customers_finished_at', null);
                    PingAllCustomersJob::dispatch(auth()->user());
                    // Cache::put('ping_customers_started')
                    Notification::make()
                        ->title('Ping All Customers job started.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PingProgressWidget::class,
        ];
    }
}
