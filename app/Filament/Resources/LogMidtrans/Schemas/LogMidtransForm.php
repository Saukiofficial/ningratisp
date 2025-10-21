<?php

namespace App\Filament\Resources\LogMidtrans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\LogMidtrans;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class LogMidtransForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('orderid')
                    ->required()
                    ->maxLength(255),
                Select::make('act')
                    ->options([
                        LogMidtrans::REQUEST => 'Request',
                        LogMidtrans::CALLBACK => 'Callback',
                    ])
                    ->required(),
                JsonColumn::make('request'),
                JsonColumn::make('response'),
            ]);
    }
}
