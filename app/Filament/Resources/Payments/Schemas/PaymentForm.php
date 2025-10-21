<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('reference_id')
                    ->required()
                    ->maxLength(100),
                DateTimePicker::make('payment_datetime')
                    ->required(),
                Toggle::make('is_cancel'),
                Textarea::make('description')
                    ->maxLength(65535),
                TextInput::make('voucher_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
