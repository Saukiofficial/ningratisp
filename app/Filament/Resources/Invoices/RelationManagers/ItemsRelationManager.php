<?php

namespace App\Filament\Resources\Invoices\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_type')
                    ->options([
                        'charge' => 'Charge',
                        'discount' => 'Discount',
                        'tax' => 'Tax',
                        'adjustment' => 'Adjustment',
                    ])
                    ->default('charge')
                    ->required(),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->required(),
                TextInput::make('unit_price')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('item_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'charge' => 'success',
                        'discount' => 'danger',
                        'tax' => 'warning',
                        'adjustment' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('quantity')
                    ->numeric(2)
                    ->alignRight(),
                TextColumn::make('unit_price')
                    ->money('IDR')
                    ->alignRight(),
                TextColumn::make('line_total')
                    ->money('IDR')
                    ->alignRight(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc');
    }
}
