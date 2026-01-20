<?php

namespace App\Filament\Resources\PppProfiles\Pages;

use App\Filament\Resources\PppProfiles\PppProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPppProfiles extends ListRecords
{
    protected static string $resource = PppProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
