<?php

namespace App\Filament\Admin\Resources\CoverageAreas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CoverageAreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
}
