<?php

namespace App\Filament\Resources\CoverageAreaResource\Pages;

use App\Filament\Resources\CoverageAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoverageAreas extends ListRecords
{
    protected static string $resource = CoverageAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
