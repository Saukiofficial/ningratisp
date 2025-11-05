<?php

namespace App\Filament\Resources\CustomerConnections\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerConnectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('ip_address')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('ping_count')
                    ->numeric(),
                TextEntry::make('packet_loss')
                    ->numeric(),
                TextEntry::make('avg_rtt')
                    ->placeholder('-'),
                TextEntry::make('last_seen')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('last_ping_output')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
