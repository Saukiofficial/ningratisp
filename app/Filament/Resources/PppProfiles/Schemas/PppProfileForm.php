<?php

namespace App\Filament\Resources\PppProfiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PppProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('profile_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('local_address')
                    ->maxLength(15),
                TextInput::make('remote_address')
                    ->maxLength(15),
                TextInput::make('dns_server')
                    ->maxLength(255),
                TextInput::make('wins_server')
                    ->maxLength(255),
                TextInput::make('rate_limit')
                    ->maxLength(50),
                TextInput::make('burst_limit')
                    ->maxLength(50),
                TextInput::make('burst_threshold')
                    ->maxLength(50),
                TextInput::make('burst_time')
                    ->maxLength(20),
                TextInput::make('session_timeout')
                    ->numeric(),
                TextInput::make('idle_timeout')
                    ->numeric(),
                TextInput::make('keepalive_timeout')
                    ->numeric(),
                Toggle::make('only_one'),
                TextInput::make('incoming_filter')
                    ->maxLength(255),
                TextInput::make('outgoing_filter')
                    ->maxLength(255),
                Select::make('bridge_learning')
                    ->options([
                        'default' => 'Default',
                        'yes' => 'Yes',
                        'no' => 'No',
                    ]),
                TextInput::make('bridge_horizon')
                    ->numeric(),
                TextInput::make('bridge_path_cost')
                    ->numeric(),
                TextInput::make('bridge_port_priority')
                    ->numeric(),
                Textarea::make('description')
                    ->maxLength(65535),
                Toggle::make('is_active'),
            ]);
    }
}
