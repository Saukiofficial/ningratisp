<?php

namespace App\Filament\Resources\CustomerConnections\Tables;

use App\Filament\Resources\CustomerConnections\Actions\PingAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class CustomerConnectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.full_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->colors([
                        'danger' => 'unknown',
                        'danger' => 'offline',
                        'success' => 'online',
                    ]),
                TextColumn::make('ping_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('packet_loss')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('avg_rtt')
                    ->searchable(),
                TextColumn::make('last_seen')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                    ]),
            ])
            ->recordActions([
                PingAction::make('ping'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->headerActions([
                Action::make('last_sync')
                    ->label('Last Sync : ' . (Cache::get('ping_all_customers_finished_at')?->diffForHumans() ?? '-'))
                    ->tooltip("Started At : " . cache('ping_all_customers_started_at') . " || Finished At : " . cache('ping_all_customers_finished_at'))
                    ->disabled()
                    ->outlined()
                    ->color('info')
            ]);
    }
}
