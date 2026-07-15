<?php

namespace App\Filament\Resources\VirtualAccounts\Pages;

use App\Filament\Resources\VirtualAccounts\VirtualAccountResource;
use Filament\Resources\Pages\ListRecords;

class ListVirtualAccounts extends ListRecords
{
    protected static string $resource = VirtualAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
