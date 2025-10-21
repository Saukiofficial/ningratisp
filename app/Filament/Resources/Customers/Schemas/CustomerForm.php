<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Packages;
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
                TextInput::make('billing_number')->visibleOn('view'),
                TextInput::make('username')
                    ->required()
                    ->maxLength(64),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                TextInput::make('service_name')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                // Select::make('ppp_profile_id')
                //     ->relationship('pppProfile', 'profile_name')
                //     ->required(),
                Select::make('package_id')
                    ->options(Packages::all()->pluck('name', 'id'))
                    ->required(),
                Toggle::make('profile_override')
                    ->visibleOn(['edit', 'view']),
                TextInput::make('local_address')
                    ->maxLength(15)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('remote_address')
                    ->maxLength(15)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('dns_server')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('wins_server')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('incoming_filter')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('outgoing_filter')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('rate_limit')
                    ->maxLength(50)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('rx_rate_limit')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                TextInput::make('tx_rate_limit')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                TextInput::make('burst_limit')
                    ->maxLength(50)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('burst_threshold')
                    ->maxLength(50)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('burst_time')
                    ->maxLength(20)
                    ->visibleOn(['edit', 'view']),
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
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                Select::make('customer_type')
                    ->options([
                        'residential' => 'Residential',
                        'business' => 'Business',
                        'corporate' => 'Corporate',
                    ])

                    ->visibleOn(['edit', 'view']),
                TextInput::make('package_name')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('monthly_fee')
                    ->numeric()

                    ->visibleOn(['edit', 'view']),
                DatePicker::make('installation_date')
                    ->visibleOn(['edit', 'view']),
                DatePicker::make('expiry_date')
                    ->visibleOn(['edit', 'view']),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'terminated' => 'Terminated',
                        'pending' => 'Pending',
                    ])

                    ->visibleOn(['edit', 'view']),
                Select::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'overdue' => 'Overdue',
                    ])

                    ->visibleOn(['edit', 'view']),
                TextInput::make('caller_id')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                Toggle::make('only_one_override')
                    ->visibleOn(['edit', 'view']),
                TextInput::make('idle_timeout_override')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                TextInput::make('keepalive_timeout_override')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                TextInput::make('session_timeout_override')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                Select::make('bridge_learning_override')
                    ->options([
                        'default' => 'Default',
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->visibleOn(['edit', 'view']),
                TextInput::make('bridge_horizon_override')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                TextInput::make('bridge_path_cost_override')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                TextInput::make('bridge_port_priority_override')
                    ->numeric()
                    ->visibleOn(['edit', 'view']),
                DateTimePicker::make('last_login')
                    ->visibleOn(['edit', 'view']),
                DateTimePicker::make('last_logout')
                    ->visibleOn(['edit', 'view']),
                TextInput::make('last_caller_id')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                TextInput::make('total_uptime')
                    ->numeric()

                    ->visibleOn(['edit', 'view']),
                TextInput::make('session_count')
                    ->numeric()

                    ->visibleOn(['edit', 'view']),
                TextInput::make('bytes_in')
                    ->numeric()

                    ->visibleOn(['edit', 'view']),
                TextInput::make('bytes_out')
                    ->numeric()

                    ->visibleOn(['edit', 'view']),
                TextInput::make('created_by')
                    ->maxLength(255)
                    ->visibleOn(['edit', 'view']),
                Textarea::make('notes')
                    ->maxLength(65535)
                    ->visibleOn(['edit', 'view']),
                Toggle::make('is_active')
                    ->visibleOn(['edit', 'view']),
            ]);
    }
}
