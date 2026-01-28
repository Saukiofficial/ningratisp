<?php

namespace App\Filament\Admin\Resources\CoverageAreas\Pages;

use App\Filament\Admin\Resources\CoverageAreas\CoverageAreaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoverageAreas extends ListRecords
{
    protected static string $resource = CoverageAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
