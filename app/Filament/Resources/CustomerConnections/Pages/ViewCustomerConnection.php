<?php

namespace App\Filament\Resources\CustomerConnections\Pages;

use App\Filament\Resources\CustomerConnections\CustomerConnectionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomerConnection extends ViewRecord
{
    protected static string $resource = CustomerConnectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
