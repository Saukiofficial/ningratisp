<?php

namespace App\Filament\Resources\Discounts\Tables;

use App\Models\Discount;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DiscountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state) => Discount::getAmountType()[$state]
                    ),
                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(function ($state, $record) {
                        return ($record->type === Discount::FIXED_AMOUNT) ?
                            'IDR ' . number_format($state, 0, ',', '.') :
                            intval($state) . '%';
                    }),
                TextColumn::make('max_discount_amount')
                    ->label('Max Amount')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('applicable_to')
                    ->label('Applicable To')
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state) => Discount::getApplicableStatus()[$state]
                    ),
                TextColumn::make('customer_category')
                    ->label('Customer Category')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('package.name')
                    ->label('Package')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Active?')
                    ->boolean(),
                // IconColumn::make('auto_apply')
                //     ->boolean()
                //     ->label('Auto Apply'),
                TextColumn::make('used_count')
                    ->label('Used')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('usage_limit')
                    ->label('Limit')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
                TernaryFilter::make('auto_apply'),
                SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed_amount' => 'Fixed Amount',
                    ]),
                SelectFilter::make('applicable_to')
                    ->options([
                        'invoice' => 'Invoice',
                        'package' => 'Package',
                        'customer' => 'Customer',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }
}
