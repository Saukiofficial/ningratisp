<?php

namespace App\Filament\Resources\TemplateMessages\Schemas;

use App\Models\TemplateMessage;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TemplateMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template Information')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('used_at')
                            ->options(TemplateMessage::getOptionLabel())
                            ->required(),

                    ])
                    ->columns()
                    ->compact()
                    ->columnSpanFull(),
                Section::make('Template message')
                    ->schema([
                        RichEditor::make('content')
                            ->default(null)
                            ->extraInputAttributes([
                                'style' => 'min-height: 20rem; max-height: 50vh; overflow-y: auto;'
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
