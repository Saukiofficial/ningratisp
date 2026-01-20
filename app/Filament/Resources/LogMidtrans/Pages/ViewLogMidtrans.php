<?php

namespace App\Filament\Resources\LogMidtrans\Pages;

use App\Filament\Resources\LogMidtrans\LogMidtransResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLogMidtrans extends ViewRecord
{
    protected static string $resource = LogMidtransResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
