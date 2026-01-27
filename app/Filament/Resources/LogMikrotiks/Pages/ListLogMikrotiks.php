<?php

namespace App\Filament\Resources\LogMikrotiks\Pages;

use App\Filament\Resources\LogMikrotiks\LogMikrotikResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLogMikrotiks extends ListRecords
{
    protected static string $resource = LogMikrotikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
