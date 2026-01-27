<?php

namespace App\Filament\Resources\LogMidtrans\Pages;

use App\Filament\Resources\LogMidtrans\LogMidtransResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLogMidtrans extends EditRecord
{
    protected static string $resource = LogMidtransResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
