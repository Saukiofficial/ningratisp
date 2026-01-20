<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_id')
                    ->required()
                    ->maxLength(100),
                TextInput::make('code')
                    ->required()
                    ->maxLength(10),
                DateTimePicker::make('expired_at')
                    ->required(),
                Textarea::make('description')
                    ->maxLength(65535),
                TextInput::make('duration')
                    ->required()
                    ->numeric(),
                Select::make('duration_type')
                    ->options([
                        'd' => 'Days',
                        'h' => 'Hours',
                    ])
                    ->required(),
                Toggle::make('status'),
                Textarea::make('external_link')
                    ->maxLength(65535),
                TextInput::make('price')
                    ->required()
                    ->numeric(),
            ]);
    }
}
