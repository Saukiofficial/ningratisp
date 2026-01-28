<?php

namespace App\Filament\Admin\Resources\CoverageAreas\Pages;

use App\Filament\Admin\Resources\CoverageAreas\CoverageAreaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCoverageArea extends EditRecord
{
    protected static string $resource = CoverageAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
