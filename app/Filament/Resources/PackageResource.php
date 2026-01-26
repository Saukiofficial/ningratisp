<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-wifi';

    protected static ?string $navigationLabel = 'Paket Internet';

    protected static ?string $modelLabel = 'Paket Internet';

    protected static ?string $navigationGroup = 'Manajemen Layanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Paket')
                    ->description('Masukkan informasi paket internet yang akan ditawarkan.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Paket')
                            ->placeholder('Contoh: Home Basic'),

                        TextInput::make('speed')
                            ->required()
                            ->maxLength(255)
                            ->label('Kecepatan')
                            ->placeholder('Contoh: 20 Mbps'),

                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Harga Bulanan'),

                        Textarea::make('description')
                            ->required()
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Jelaskan fitur paket ini...'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Paket')
                    ->weight('bold'),

                TextColumn::make('speed')
                    ->label('Kecepatan')
                    ->badge()
                    ->color('info'),

                TextColumn::make('price')
                    ->money('IDR') // Locale removed to prevent errors if not configured
                    ->sortable()
                    ->label('Harga'),

                TextColumn::make('description')
                    ->limit(50)
                    ->label('Deskripsi')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat Pada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
