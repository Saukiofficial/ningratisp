<?php

namespace App\Filament\Admin\Resources\Complaints\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Tiket')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Pelanggan')
                            ->disabled()
                            ->required(),

                        TextInput::make('subject')
                            ->label('Judul Keluhan')
                            ->disabled()
                            ->required(),

                        Textarea::make('description')
                            ->label('Isi Laporan')
                            ->disabled()
                            ->columnSpanFull(),


                        Select::make('status')
                            ->label('Status Tiket')
                            ->options([
                                'pending' => 'Menunggu (Pending)',
                                'resolved' => 'Selesai (Resolved)',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(2),
            ]);
    }
}
