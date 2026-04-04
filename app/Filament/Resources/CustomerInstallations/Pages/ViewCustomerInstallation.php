<?php

namespace App\Filament\Resources\CustomerInstallations\Pages;

use App\Filament\Resources\CustomerInstallations\CustomerInstallationResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewCustomerInstallation extends ViewRecord
{
    protected static string $resource = CustomerInstallationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToHome')
                ->icon(Heroicon::OutlinedChevronLeft)
                ->url(static::getResource()::getUrl('index'))
                ->color('gray'),
            EditAction::make()
                ->icon(Heroicon::OutlinedPencilSquare),
        ];
    }
}
