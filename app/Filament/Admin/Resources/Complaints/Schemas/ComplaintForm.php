<?php

namespace App\Filament\Admin\Resources\Complaints\Schemas;

use App\Models\Complaint;
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
                    ->columnSpanFull()
                    ->schema([
                        Select::make('user_id')
                            ->relationship('customer', 'username')
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
                            ->options(Complaint::getStatusLabel())
                            ->required()
                            ->native(false),
                    ])->columns(2),
            ]);
    }
}
