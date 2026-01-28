<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Models\Asset;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Grid as InfoGrid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use UnitEnum;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Kelola Aset';

    protected static ?string $modelLabel = 'Aset';

    protected static string|UnitEnum|null $navigationGroup = 'Inventaris';

    public static function form(Schema $form): Schema
    {
        return $form
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

    public static function table(Table $table): Table
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
            ->actions([
                // KOSONG - Klik row untuk lihat detail
            ])
            // ->bulkActions([
            //     BulkActionGroup::make([
            //         DeleteBulkAction::make()
            //             ->requiresConfirmation()
            //             ->modalHeading('Hapus Aset Terpilih')
            //             ->modalDescription('Apakah Anda yakin ingin menghapus aset yang dipilih?')
            //             ->successNotification(
            //                 Notification::make()
            //                     ->success()
            //                     ->title('Aset Dihapus')
            //                     ->body('Aset berhasil dihapus dari sistem.')
            //             ),
            //     ]),
            // ])
            ->recordUrl(
                fn($record) => route('filament.admin.resources.assets.view', ['record' => $record])
            );
    }

    public static function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make('Detail Aset')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Foto Aset')
                                    ->columnSpan(1)
                                    ->height(200)
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'view' => Pages\ViewAsset::route('/{record}'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
