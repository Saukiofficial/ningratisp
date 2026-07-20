<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\PppProfile;
use App\Services\ZeroTierProxyService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
use Illuminate\Support\Facades\Http;
use Spatie\Ping\Ping;

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
                    ->toggleable(isToggledHiddenByDefault: true)
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
                IconColumn::make('can_remote')->boolean()
                    ->label('Remote')
                    ->trueIcon(Heroicon::OutlinedCheckBadge)
                    ->falseIcon(Heroicon::OutlinedXMark)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('auto_isolir')->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckBadge)
                    ->falseIcon(Heroicon::OutlinedXMark)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Register')
                    ->date('d F Y, H:i:s')
                    ->sortable(),
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
                Filter::make('can_remote')
                    ->label('Active Remote')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('can_remote', true)),
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
                    Action::make('check_remote')
                        ->name('Check Remote Router')
                        ->icon(Heroicon::OutlinedCursorArrowRipple)
                        ->action(
                            fn(Customer $record) => self::checkRemoteRouter($record)
                        )
                        ->requiresConfirmation()
                        ->modalSubmitActionLabel('Check')
                        ->modalDescription('Ini hanya bekerja jika dalam satu jaringan dengan mikrotik langsung dan akan memaktu waktu sedikit lebih lama'),
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
                        }),
                    DeleteAction::make()
                        ->visible(
                            fn(Customer $record) => !$record->invoices()->exists()
                        )
                        ->action(function (Customer $record, DeleteAction $action) {
                            $removePpoe = (new MikrotikAPINative())->deletePpoeCustomer($record->username);
                            if (!empty($removePpoe['error']) && $removePpoe['status'] == true) {
                                Notification::make()
                                    ->title('Failed remove PPPoE Customer')
                                    ->body($removePpoe['message'] ?? '')
                                    ->danger()->send();
                                $action->halt(true);
                                return;
                            }

                            $record->delete();
                            Notification::make()
                                ->title('Success remove PPPoE Customer')
                                ->success()->send();
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

    private static function checkRemoteRouter(Customer $customer): bool
    {
        $ping = (new Ping('10.20.30.1'))->timeoutInSeconds(5)->count(3)->run();
        if (!$ping->isSuccess()) {
            Notification::make()
                ->title('Action can not proceed')
                ->body("Your're not under router network")
                ->danger()->send();

            return false;
        }

        try {
            $response = Http::timeout(5)->get($customer->remote_address);
            $status = $response->successful();
            $message = $status ? $response->status() . ' OK' : 'HTTP ' . $response->status();
        } catch (\Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }

        $customer->update(['can_remote' => $status]);
        $notif = Notification::make()
            ->title('Remote check (' . $customer->username . ') : ' . ($status ? 'Success' : 'Failed'))
            ->body($message);
        $status ? $notif->success() : $notif->danger();
        $notif->send();

        return $status;
    }
}
