<?php

namespace App\Filament\Resources\PppProfiles\Pages;

use App\Filament\Resources\PppProfiles\PppProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPppProfile extends EditRecord
{
    protected static string $resource = PppProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
