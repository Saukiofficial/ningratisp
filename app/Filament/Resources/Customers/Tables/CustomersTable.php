<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\PppProfile;
use App\Services\ZeroTierProxyService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('billing_number')
                    ->label('Billing Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('local_address')
                    ->searchable()
                    ->sortable(true, fn(Builder $query) => $query->orderByRaw('INET_ATON(local_address)')),
                TextColumn::make('remote_address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: fn(Builder $query) => $query->orderByRaw('INET_ATON(remote_address)')),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_name')
                    ->searchable(),
                // TextColumn::make('pppProfile.profile_name')
                //     ->label('PPP Profile')
                //     ->sortable(),
                TextColumn::make('activePackage.package.name')
                    // ->formatStateUsing(fn(string $state) => $state . '-')
                    ->suffix(fn(Customer $record) => " ({$record->pppProfile->profile_name})"),
                TextColumn::make('customer_category')
                    ->label('Category')
                    ->formatStateUsing(
                        fn($state) => Discount::getCategoryStatus()[$state]
                    ),
                IconColumn::make('auto_isolir')->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckBadge)
                    ->falseIcon(Heroicon::OutlinedXMark),
                TextColumn::make('isolir_at')
                    ->sortable(),
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
                    ->query(fn(Builder $query) => $query->whereNotNull('isolir_at')),
                TernaryFilter::make('auto_isolir')
                    ->label('Auto Isolir'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    // Action::make('test_router')
                    // ->action(
                    //     fn()=>
                    // ),
                    Action::make('open_router')
                        ->name('Router')
                        ->icon(Heroicon::OutlinedCog)
                        ->url(
                            fn(Customer $record) => 'http://' . $record->remote_address
                        )
                        ->openUrlInNewTab(),
                    Action::make('open_router_outside')
                        ->hidden()
                        ->action(function (Customer $record, ZeroTierProxyService $proxyService) {

                            $proxy = $proxyService->createOrGetProxy(
                                $record->id,
                                $record->remote_address
                            );

                            if (!$proxy) {
                                Notification::make()
                                    ->title('Failed Create')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            Notification::make()
                                ->title('Success Created')
                                ->body('URL : ' . $proxy->proxy_url)
                                ->actions([
                                    Action::make('open_router_url')
                                        ->button()
                                        ->url($proxy->proxy_url)
                                ])
                                ->success()
                                ->send();
                        })
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
