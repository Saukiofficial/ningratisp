<?php

namespace App\Filament\Resources\CustomerInstallations\Schemas;

use App\Models\CustomerInstallationOrder;
use App\Models\Packages;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInstallationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->label('No. Whatsapp')
                            ->mask('9999-9999-999999')
                            ->placeholder('0812-3456-789'),
                        Select::make('ppp_area_id')
                            ->label('Area')
                            ->relationship('area', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        Select::make('package_id')
                            ->label('Package')
                            ->options(Packages::getDropDownWithPpp())
                            ->required()
                            ->preload()
                            ->searchable(),
                    ])->columns(2),

                Section::make('Installation Details')
                    ->schema([
                        Select::make('status')
                            ->options(CustomerInstallationOrder::getStatusLabel())
                            ->required()
                            ->default(CustomerInstallationOrder::STATUS_PENDING)
                            ->live(),
                        TextInput::make('cancelled_reason')
                            ->visible(fn($get) => $get('status') === CustomerInstallationOrder::STATUS_CANCELLED)
                            ->required(fn($get) => $get('status') === CustomerInstallationOrder::STATUS_CANCELLED),
                        DateTimePicker::make('installation_date')
                            ->required(),
                        DateTimePicker::make('finished_date')
                            ->visible(fn($get) => $get('status') === CustomerInstallationOrder::STATUS_DONE),
                        TextInput::make('maps_link')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('reffer_by')
                            ->label('Referred By')
                            ->maxLength(255),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
