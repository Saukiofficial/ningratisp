<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->required()
                    ->maxLength(64),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                TextInput::make('service_name')
                    ->maxLength(255),
                Select::make('ppp_profile_id')
                    ->relationship('pppProfile', 'profile_name')
                    ->required(),
                Toggle::make('profile_override'),
                TextInput::make('local_address')
                    ->maxLength(15),
                TextInput::make('remote_address')
                    ->maxLength(15),
                TextInput::make('dns_server')
                    ->maxLength(255),
                TextInput::make('wins_server')
                    ->maxLength(255),
                TextInput::make('incoming_filter')
                    ->maxLength(255),
                TextInput::make('outgoing_filter')
                    ->maxLength(255),
                TextInput::make('rate_limit')
                    ->maxLength(50),
                TextInput::make('rx_rate_limit')
                    ->numeric(),
                TextInput::make('tx_rate_limit')
                    ->numeric(),
                TextInput::make('burst_limit')
                    ->maxLength(50),
                TextInput::make('burst_threshold')
                    ->maxLength(50),
                TextInput::make('burst_time')
                    ->maxLength(20),
                TextInput::make('full_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->maxLength(255),
                Textarea::make('address')
                    ->maxLength(65535),
                TextInput::make('id_number')
                    ->maxLength(255),
                Select::make('customer_type')
                    ->options([
                        'residential' => 'Residential',
                        'business' => 'Business',
                        'corporate' => 'Corporate',
                    ])
                    ->required(),
                TextInput::make('package_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('monthly_fee')
                    ->numeric()
                    ->required(),
                DatePicker::make('installation_date'),
                DatePicker::make('expiry_date'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'terminated' => 'Terminated',
                        'pending' => 'Pending',
                    ])
                    ->required(),
                Select::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'overdue' => 'Overdue',
                    ])
                    ->required(),
                TextInput::make('caller_id')
                    ->maxLength(255),
                Toggle::make('only_one_override'),
                TextInput::make('idle_timeout_override')
                    ->numeric(),
                TextInput::make('keepalive_timeout_override')
                    ->numeric(),
                TextInput::make('session_timeout_override')
                    ->numeric(),
                Select::make('bridge_learning_override')
                    ->options([
                        'default' => 'Default',
                        'yes' => 'Yes',
                        'no' => 'No',
                    ]),
                TextInput::make('bridge_horizon_override')
                    ->numeric(),
                TextInput::make('bridge_path_cost_override')
                    ->numeric(),
                TextInput::make('bridge_port_priority_override')
                    ->numeric(),
                DateTimePicker::make('last_login'),
                DateTimePicker::make('last_logout'),
                TextInput::make('last_caller_id')
                    ->maxLength(255),
                TextInput::make('total_uptime')
                    ->numeric()
                    ->required(),
                TextInput::make('session_count')
                    ->numeric()
                    ->required(),
                TextInput::make('bytes_in')
                    ->numeric()
                    ->required(),
                TextInput::make('bytes_out')
                    ->numeric()
                    ->required(),
                TextInput::make('created_by')
                    ->maxLength(255),
                Textarea::make('notes')
                    ->maxLength(65535),
                Toggle::make('is_active'),
            ]);
    }
}
