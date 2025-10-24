<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\RelationManagers\ActiveInvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\AllInvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\CustomerPackagesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\PaymentsRelationManager;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
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
                ->requiresConfirmation(),
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
