<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\on;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('details')
                    ->schema([
                        TextEntry::make('billing_number'),
                        TextEntry::make('username'),
                        TextEntry::make('activePackage.package.name')
                    ])
                    ->columnSpanFull()
                    ->columns(3),
            ]);
    }
}
