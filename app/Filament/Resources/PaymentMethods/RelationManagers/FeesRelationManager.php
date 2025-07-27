<?php

namespace App\Filament\Resources\PaymentMethods\RelationManagers;

use App\Filament\Resources\Fees\Tables\FeesTable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FeesRelationManager extends RelationManager
{
    protected static string $relationship = 'fees';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('unit')
                    ->required()
                    ->default('n'),
                DateTimePicker::make('started_at')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return FeesTable::configure($table);
    }
}
