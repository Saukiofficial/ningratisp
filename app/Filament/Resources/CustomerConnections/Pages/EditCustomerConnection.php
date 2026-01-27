<?php

namespace App\Filament\Resources\CustomerConnections\Pages;

use App\Filament\Resources\CustomerConnections\CustomerConnectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerConnection extends EditRecord
{
    protected static string $resource = CustomerConnectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
