<?php

namespace App\Filament\Resources\Discounts\Schemas;

use App\Models\Discount;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Illuminate\Validation\Rule;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Discount Information')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('code')
                            ->placeholder('Generate or input manual Code')
                            ->required()
                            ->maxLength(10)
                            ->trim()
                            ->mask(RawJs::make('$input.toUpperCase()'))
                            ->rules([
                                fn(?Discount $record) => Rule::unique('discounts', 'code')->ignore($record?->id)
                            ])
                            ->prefixAction(
                                Action::make('generate')
                                    ->label('Generate')
                                    ->icon(Heroicon::ArrowPath)
                                    ->action(
                                        fn(Set $set) => $set('code', Discount::generateRandomCode())
                                    )
                            ),
                        DatePicker::make('start_date')
                            ->native(false)
                            ->default(now())
                            ->nullable(),
                        DatePicker::make('end_date')
                            ->native(false)
                            ->nullable(),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        // Toggle::make('auto_apply')
                        //     ->label('Auto Apply')
                        //     ->required(),
                    ]),
                Section::make('Application Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        Select::make('type')
                            ->required()
                            ->options(Discount::getAmountType())
                            ->default(Discount::FIXED_AMOUNT)
                            ->reactive()
                            ->afterStateUpdated(fn(Set $set) => $set('max_discount_amount', 0))
                            ->native(false),
                        Select::make('applicable_to')
                            ->required()
                            ->options(Discount::getApplicableStatus())
                            ->default(Discount::FOR_INVOICE)
                            ->native(false),
                        Select::make('customer_category')
                            ->label('Customer Category')
                            ->options(Discount::getCategoryStatus())
                            ->native(false)
                            ->nullable()
                            ->default(Discount::TYPE_NORMAL),
                        Select::make('package_id')
                            ->label('Package')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->nullable(),
                        TextInput::make('value')
                            ->label('Value')
                            ->numeric(
                                fn(Get $get) => $get('type') == Discount::PERCENTAGE
                            )
                            ->required()
                            ->live(true)
                            ->prefix(
                                fn(Get $get) => $get('type') == Discount::FIXED_AMOUNT ? 'Rp' : null
                            )
                            ->suffix(
                                fn(Get $get) => $get('type') == Discount::PERCENTAGE ? '%' : null
                            )
                            ->maxValue(
                                fn(Get $get) => $get('type') == Discount::PERCENTAGE ? 100 : null
                            )
                            ->afterStateUpdated(
                                fn($state, Get $get, Set $set) => $get('type') == Discount::FIXED_AMOUNT ? $set('max_discount_amount', $state) : null
                            )
                            ->mask(fn(Get $get) => $get('type') == Discount::FIXED_AMOUNT ? RawJs::make('$money($input)') : null)
                            ->stripCharacters(','),
                        TextInput::make('max_discount_amount')
                            ->label('Max Discount Amount')
                            // ->numeric()
                            ->nullable()
                            ->prefix('Rp')
                            ->disabled(
                                fn(Get $get) => $get('type') == Discount::FIXED_AMOUNT
                            )
                            ->required(
                                fn(Get $get) => $get('type') == Discount::PERCENTAGE
                            )
                            ->dehydrated(true)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(','),
                    ]),
                Section::make('Additional Settings')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('usage_limit')
                            ->helperText('Empty for unlimited')
                            ->numeric(),
                        TextInput::make('max_per_user')
                            ->label('User Limit')
                            ->helperText('Maximal per user can used')
                            ->default(1)
                            ->numeric()
                            ->required(),
                        Toggle::make('claimable')
                            ->label('Customer Claim')
                            ->hint(
                                fn($get) => $get('claimable') ? 'Dapat di klaim oleh customer' : 'Apply admin'
                            )
                            ->reactive()
                            ->helperText('Voucher dapat diklaim manual oleh customer atau apply oleh admin')
                            ->default(true)
                    ])
            ]);
    }
}
