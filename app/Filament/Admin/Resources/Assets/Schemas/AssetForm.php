<?php

namespace App\Filament\Admin\Resources\Assets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Perangkat')
                    ->description('Detail spesifikasi aset.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Aset')
                            ->placeholder('Contoh: Mikrotik RB750'),

                        TextInput::make('serial_number')
                            ->label('Serial Number (SN)')
                            ->unique(ignoreRecord: true),

                        Select::make('type')
                            ->required()
                            ->label('Jenis Aset')
                            ->options([
                                'Router' => 'Router',
                                'Modem' => 'Modem / ONT',
                                'Switch' => 'Switch / Hub',
                                'Cable' => 'Kabel (Roll)',
                                'Tools' => 'Peralatan Teknis',
                                'Server' => 'Server / OLT',
                            ])
                            ->native(false),

                        Select::make('status')
                            ->required()
                            ->label('Status Kondisi')
                            ->options([
                                'available' => 'Tersedia (Gudang)',
                                'in_use' => 'Sedang Dipakai (Pelanggan)',
                                'maintenance' => 'Perbaikan',
                                'broken' => 'Rusak / Afkir',
                            ])
                            ->default('available')
                            ->native(false),
                    ])->columns(2),

                Section::make('Keuangan & Lainnya')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('purchase_date')
                                    ->label('Tanggal Beli'),

                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->label('Harga Beli'),

                                FileUpload::make('image')
                                    ->image()
                                    ->directory('assets-images')
                                    ->label('Foto Aset'),
                            ]),

                        Textarea::make('description')
                            ->label('Catatan Tambahan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
