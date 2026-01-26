<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoverageAreaResource\Pages;
use App\Models\CoverageArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Tambahkan import komponen Form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
// Tambahkan import komponen Table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class CoverageAreaResource extends Resource
{
    protected static ?string $model = CoverageArea::class;


    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Coverage Area';

    protected static ?string $modelLabel = 'Area Jangkauan';

    protected static ?string $navigationGroup = 'Manajemen Layanan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Wilayah')
                    ->description('Tambahkan data wilayah yang tercover layanan NingratNet.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Desa / Kelurahan')
                            ->placeholder('Contoh: Lenteng'),

                        TextInput::make('district')
                            ->required()
                            ->maxLength(255)
                            ->label('Kecamatan')
                            ->placeholder('Contoh: Lenteng Timur'),

                        TextInput::make('city')
                            ->required()
                            ->maxLength(255)
                            ->label('Kota / Kabupaten')
                            ->placeholder('Contoh: Sumenep'),
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
                    ->label('Desa/Kelurahan')
                    ->weight('bold'),

                TextColumn::make('district')
                    ->searchable()
                    ->sortable()
                    ->label('Kecamatan'),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable()
                    ->label('Kota/Kabupaten'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat'),
            ])
            ->filters([

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
            'index' => Pages\ListCoverageAreas::route('/'),
            'create' => Pages\CreateCoverageArea::route('/create'),
            'edit' => Pages\EditCoverageArea::route('/{record}/edit'),
        ];
    }
}
