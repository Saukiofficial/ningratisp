<?php

namespace App\Filament\Resources\TemplateMessages\Pages;

use App\Filament\Resources\TemplateMessages\TemplateMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTemplateMessage extends EditRecord
{
    protected static string $resource = TemplateMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
