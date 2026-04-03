<?php

namespace App\Filament\Resources\CustomerInstallations\Pages;

use App\Filament\Resources\CustomerInstallations\CustomerInstallationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerInstallation extends EditRecord
{
    protected static string $resource = CustomerInstallationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
