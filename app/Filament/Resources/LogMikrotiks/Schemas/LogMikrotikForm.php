<?php

namespace App\Filament\Resources\LogMikrotiks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class LogMikrotikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('action')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Grid::make()
                    ->schema([
                        JsonColumn::make('request'),
                        JsonColumn::make('response'),
                    ])
                    ->columnSpanFull()
                // Toggle::make('status'),
            ]);
    }
}
