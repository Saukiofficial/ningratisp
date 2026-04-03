<?php

namespace App\Filament\Resources\CustomerInstallations\Pages;

use App\Filament\Resources\CustomerInstallations\CustomerInstallationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomerInstallation extends ViewRecord
{
    protected static string $resource = CustomerInstallationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
