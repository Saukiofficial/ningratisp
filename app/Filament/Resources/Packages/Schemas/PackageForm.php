<?php

namespace App\Filament\Resources\Packages\Schemas;

use App\Models\PppProfile;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        Select::make('ppp_profile_id')
                            ->label('PPP Profile')
                            ->options(PppProfile::all()->pluck('profile_name', 'id'))
                            ->searchable(),
                        TextInput::make('duration_months')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('price')
                            ->required()
                            ->numeric(),
                        TextInput::make('discount_percent')
                            ->numeric()
                            ->default(0),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->required(),
                    ]),
            ]);
    }
}
