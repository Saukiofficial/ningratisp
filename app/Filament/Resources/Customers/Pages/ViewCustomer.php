<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\RelationManagers\ActiveInvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\AllInvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\CustomerPackagesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\PaymentsRelationManager;
use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('isolir')
                ->color('danger')
                ->visible(fn(Customer $record) => empty($record->isolir_at))
                ->requiresConfirmation()
                ->action(function (Customer $record) {

                    $response = (new MikrotikAPINative)->isolirClient($record->username, true);

                    if (isset($response['error'])) {
                        Notification::make()
                            ->title('Isolir Failed : ' . $record->username)
                            ->body(json_encode($response))
                            ->danger()
                            ->send();

                        return;
                    }

                    $record->update([
                        'isolir_at' => now(),
                        'comment' => $response['comment']
                    ]);

                    Notification::make()
                        ->title('Customer Isolated')
                        ->body('Customer ' . $record->username . ' has been isolated successfully.')
                        ->success()
                        ->send();
                }),


            Action::make('open_isolir')
                ->color('success')
                ->visible(fn(Customer $record) => ! empty($record->isolir_at))
                ->requiresConfirmation()
                ->action(function (Customer $record) {

                    $response = (new MikrotikAPINative)->isolirClient($record->username, false);

                    if (isset($response['error'])) {
                        Notification::make()
                            ->title('Open Isolir Failed : ' . $record->username)
                            ->body(json_encode($response))
                            ->danger()
                            ->send();

                        return;
                    }

                    $record->update([
                        'isolir_at' => null,
                        'comment' => null
                    ]);

                    Notification::make()
                        ->title('Isolir Opened')
                        ->body('Customer ' . $record->username . ' is now active.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            ActiveInvoicesRelationManager::class,
            AllInvoicesRelationManager::class,
            PaymentsRelationManager::class,
            CustomerPackagesRelationManager::class,
        ];
    }
}
