<?php

namespace App\Filament\Resources\LogMikrotiks\Pages;

use App\Filament\Resources\LogMikrotiks\LogMikrotikResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLogMikrotik extends EditRecord
{
    protected static string $resource = LogMikrotikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
