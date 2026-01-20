<?php

namespace App\Filament\Resources\LogMidtrans\Pages;

use App\Filament\Resources\LogMidtrans\LogMidtransResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLogMidtrans extends ListRecords
{
    protected static string $resource = LogMidtransResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
