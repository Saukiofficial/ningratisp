<?php

namespace App\Filament\Resources\Discounts\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerUsedVoucherRelationManager extends RelationManager
{
    protected static string $relationship = 'customers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('')
                //     ->required()
                //     ->maxLength(255),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('applied_at')
                    ->date('d F Y, H:i:s')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // AttachAction::make(),
            ])
            ->recordActions([
                // 
            ]);
    }
}
