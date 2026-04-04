<?php

namespace App\Filament\Resources\CustomerInstallations\Pages;

use App\Filament\Resources\CustomerInstallations\CustomerInstallationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditCustomerInstallation extends EditRecord
{
    protected static string $resource = CustomerInstallationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon(Heroicon::OutlinedEye),
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
