<?php

namespace App\Filament\Admin\Resources\Assets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto')
                    ->square(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('Nama Aset')
                    ->description('Klik untuk melihat detail')
                    ->color('primary'),

                TextColumn::make('type')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->label('Tipe'),

                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->color(fn(string $state): string => match ($state) {
                        'available' => 'success',
                        'in_use' => 'info',
                        'maintenance' => 'warning',
                        'broken' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'available' => 'Tersedia',
                        'in_use' => 'Dipakai',
                        'maintenance' => 'Perbaikan',
                        'broken' => 'Rusak',
                        default => $state,
                    }),

                TextColumn::make('serial_number')
                    ->searchable()
                    ->label('SN')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'available' => 'Tersedia',
                        'in_use' => 'Dipakai',
                        'maintenance' => 'Perbaikan',
                        'broken' => 'Rusak',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'Router' => 'Router',
                        'Modem' => 'Modem',
                        'Switch' => 'Switch',
                        'Server' => 'Server',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->recordUrl(
                fn($record) => route('filament.admin.resources.assets.view', ['record' => $record])
            );
    }
}
