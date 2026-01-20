<?php

namespace App\Filament\Resources\PppProfiles\Pages;

use App\Filament\Resources\PppProfiles\PppProfileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPppProfile extends ViewRecord
{
    protected static string $resource = PppProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
