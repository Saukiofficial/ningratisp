<?php

namespace App\Filament\Resources\TemplateMessages\Pages;

use App\Filament\Resources\TemplateMessages\TemplateMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTemplateMessages extends ListRecords
{
    protected static string $resource = TemplateMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
