<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use App\Models\PaymentMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->maxLength(255),
                TextInput::make('logo')
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535),
                Select::make('category')
                    ->options((new PaymentMethod)->getCategoryName())
                    ->required(),
            ]);
    }
}
