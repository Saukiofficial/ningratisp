<?php

namespace App\Filament\Admin\Resources\Customers\Tables;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\PppProfile;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('full_name')
                    ->searchable(),
                // TextColumn::make('pppProfile.profile_name')
                //     ->label('PPP Profile')
                //     ->sortable(),
                TextColumn::make('activePackage.package.name')
                    // ->formatStateUsing(fn(string $state) => $state . '-')
                    ->suffix(fn(Customer $record) => " ({$record->pppProfile->profile_name})"),
                TextColumn::make('isolir_at')
                    ->sortable(),
                TextColumn::make('customer_category')
                    ->label('Category')
                    ->formatStateUsing(
                        fn($state) => Discount::getCategoryStatus()[$state]
                    )
                // TextColumn::make('status')
                //     ->sortable(),
                // TextColumn::make('payment_status')
                //     ->sortable(),
                // IconColumn::make('is_active')
                //     ->boolean()
                //     ->sortable(),
            ])
            ->filters([
                SelectFilter::make('ppp_profile_id')
                    ->label('Paket')
                    ->options(PppProfile::whereNotNull('rate_limit')->pluck('profile_name', 'id')),
                SelectFilter::make('customer_category')
                    ->label('Category')
                    ->options(Discount::getCategoryStatus()),
                Filter::make('isolir_at')
                    ->label('Isolir')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->whereNotNull('isolir_at'))
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                ])
            ])
            // ->toolbarActions([
            //     BulkActionGroup::make([
            //         DeleteBulkAction::make(),
            //     ]),
            // ])
            ->defaultSort('id', 'desc');
    }
}
