<?php

namespace App\Filament\Resources\LogMikrotiks\Pages;

use App\Filament\Resources\LogMikrotiks\LogMikrotikResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLogMikrotik extends ViewRecord
{
    protected static string $resource = LogMikrotikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
