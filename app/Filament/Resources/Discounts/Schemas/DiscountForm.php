<?php

namespace App\Filament\Resources\Discounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Discount Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('code')
                            ->required()
                            ->maxLength(50),
                        Select::make('type')
                            ->required()
                            ->options([
                                'percentage' => 'Percentage',
                                'fixed_amount' => 'Fixed Amount',
                            ])
                            ->native(false),
                        Select::make('applicable_to')
                            ->required()
                            ->options([
                                'invoice' => 'Invoice',
                                'package' => 'Package',
                                'customer' => 'Customer',
                            ])
                            ->native(false),
                        Select::make('customer_category')
                            ->label('Customer Category')
                            ->options([
                                'free_forever' => 'Free Forever',
                                'loan' => 'Loan',
                                'normal' => 'Normal',
                            ])
                            ->native(false)
                            ->nullable(),
                        Select::make('package_id')
                            ->label('Package')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->nullable(),
                        TextInput::make('value')
                            ->label('Value')
                            ->numeric()
                            ->required(),
                        TextInput::make('max_discount_amount')
                            ->label('Max Discount Amount')
                            ->numeric()
                            ->nullable(),
                        DatePicker::make('start_date')
                            ->native(false)
                            ->nullable(),
                        DatePicker::make('end_date')
                            ->native(false)
                            ->nullable(),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->required(),
                        Toggle::make('auto_apply')
                            ->label('Auto Apply')
                            ->required(),
                    ]),
            ]);
    }
}
