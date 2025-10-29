<?php

namespace App\Filament\Resources\CustomerConnections\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerConnectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required()
                    ->numeric()
                    ->disabled(),
                TextInput::make('ip_address')
                    ->disabled(),
                TextInput::make('status')
                    ->required()
                    ->default('unknown')
                    ->disabled(),
                TextInput::make('ping_count')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled(),
                TextInput::make('packet_loss')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled(),
                TextInput::make('avg_rtt')
                    ->disabled(),
                DateTimePicker::make('last_seen')
                    ->disabled(),
                Textarea::make('last_ping_output')
                    ->columnSpanFull()
                    ->disabled(),
            ]);
    }
}
