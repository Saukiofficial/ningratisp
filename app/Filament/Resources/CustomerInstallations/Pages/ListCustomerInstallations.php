<?php

namespace App\Filament\Resources\CustomerInstallations\Pages;

use App\Filament\Resources\CustomerInstallations\CustomerInstallationResource;
use App\Filament\Widgets\CustomerInstallationOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerInstallations extends ListRecords
{
    protected static string $resource = CustomerInstallationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerInstallationOverview::class,
        ];
    }
}
