<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\RelationManagers\ActiveInvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\AllInvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\CustomerPackagesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\PaymentsRelationManager;
use App\Models\CustomerPackages;
use App\Models\Packages;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rule;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
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
