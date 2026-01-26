<?php

namespace App\Filament\Resources\CoverageAreaResource\Pages;

use App\Filament\Resources\CoverageAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoverageArea extends EditRecord
{
    protected static string $resource = CoverageAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
