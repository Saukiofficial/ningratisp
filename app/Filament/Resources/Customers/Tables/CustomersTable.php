<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\PppProfile;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('billing_number')
                    ->label('Billing Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('full_name')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('pppProfile.profile_name')
                    ->label('PPP Profile')
                    ->sortable(),
                TextColumn::make('status')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('ppp_profile_id')
                    ->label('Paket')
                    ->options(PppProfile::whereNotNull('rate_limit')->pluck('profile_name', 'id'))
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
