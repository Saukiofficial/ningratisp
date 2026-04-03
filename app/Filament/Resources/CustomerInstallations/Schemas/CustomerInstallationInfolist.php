<?php

namespace App\Filament\Resources\CustomerInstallations\Schemas;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\CustomerInstallationOrder;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInstallationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('phone'),
                        TextEntry::make('area.name')
                            ->label('Area'),
                        TextEntry::make('package.name')
                            ->label('Package'),
                    ])->columns(2),

                Section::make('Installation Details')
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                CustomerInstallationOrder::STATUS_PENDING => 'gray',
                                CustomerInstallationOrder::STATUS_ON_PROGRESS => 'warning',
                                CustomerInstallationOrder::STATUS_DONE => 'success',
                                CustomerInstallationOrder::STATUS_CANCELLED => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => CustomerInstallationOrder::getStatusLabel()[$state] ?? $state),
                        TextEntry::make('cancelled_reason')
                            ->visible(fn (CustomerInstallationOrder $record) => $record->status === CustomerInstallationOrder::STATUS_CANCELLED),
                        TextEntry::make('installation_date')
                            ->dateTime(),
                        TextEntry::make('finished_date')
                            ->dateTime()
                            ->visible(fn (CustomerInstallationOrder $record) => $record->status === CustomerInstallationOrder::STATUS_DONE),
                        TextEntry::make('maps_link')
                            ->url(
                                fn (CustomerInstallationOrder $record) => $record->maps_link
                            )
                            ->openUrlInNewTab(),
                        TextEntry::make('reffer_by')
                            ->label('Referred By'),
                        TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Associated Customer')
                    ->schema([
                        TextEntry::make('customer.username')
                            ->label('Username')
                            ->url(fn (CustomerInstallationOrder $record) => $record->customer_id ? CustomerResource::getUrl('view', ['record' => $record->customer_id]) : null),
                    ])
                    ->visible(fn (CustomerInstallationOrder $record) => $record->customer_id !== null),
            ]);
    }
}
