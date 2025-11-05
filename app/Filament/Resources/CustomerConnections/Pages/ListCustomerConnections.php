<?php

namespace App\Filament\Resources\CustomerConnections\Pages;

use App\Filament\Resources\CustomerConnections\CustomerConnectionResource;
use App\Filament\Resources\CustomerConnections\Widgets\PingProgressWidget;
use App\Jobs\PingAllCustomersJob;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
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
                ->schema([
                    Toggle::make('is_all')
                        ->label('All Customers')
                        ->default(true)
                        ->live(),
                    TextInput::make('total')
                        ->integer()
                        ->required(
                            fn(Get $get) => !$get('is_all')
                        )
                        ->disabled(
                            fn(Get $get) => $get('is_all')
                        ),
                ])
                ->requiresConfirmation()
                ->action(function (array $data) {
                    Cache::put('ping_all_customers_total', 0);
                    Cache::put('ping_all_customers_processed', 0);
                    Cache::put('ping_all_customers_online', 0);
                    Cache::put('ping_all_customers_offline', 0);
                    Cache::put('ping_all_customers_started_at', now());
                    Cache::put('ping_all_customers_finished_at', null);

                    $limit = !$data['is_all'] ? intval($data['total']) : null;
                    PingAllCustomersJob::dispatch(auth()->user(), $limit);

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
