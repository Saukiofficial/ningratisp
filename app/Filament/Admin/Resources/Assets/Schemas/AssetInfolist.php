<?php

namespace App\Filament\Admin\Resources\Assets\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class AssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Aset')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Foto Aset')
                                    ->columnSpan(1)
                                    ->imageHeight(200)
                                    ->square(),

                                Grid::make(1)
                                    ->columnSpan(2)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Nama Aset')
                                            ->weight('bold')
                                            ->size(TextSize::Large),

                                        TextEntry::make('serial_number')
                                            ->label('Serial Number')
                                            ->fontFamily('mono')
                                            ->copyable(),

                                        TextEntry::make('status')
                                            ->badge()
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
                                    ]),
                            ]),
                    ]),

                Section::make('Informasi Tambahan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('type')->label('Tipe Perangkat'),
                                TextEntry::make('purchase_date')->label('Tanggal Beli')->date(),
                                TextEntry::make('price')->label('Harga')->money('IDR'),
                            ]),

                        TextEntry::make('description')
                            ->label('Catatan / Deskripsi')
                            ->columnSpanFull()
                            ->markdown(),

                        TextEntry::make('created_at')
                            ->label('Terdaftar Sejak')
                            ->dateTime()
                            ->color('gray'),
                    ])
            ]);
    }
}
