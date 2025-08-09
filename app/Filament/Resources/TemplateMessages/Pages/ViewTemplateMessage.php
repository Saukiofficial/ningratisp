<?php

namespace App\Filament\Resources\TemplateMessages\Pages;

use App\Filament\Resources\TemplateMessages\TemplateMessageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTemplateMessage extends ViewRecord
{
    protected static string $resource = TemplateMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
