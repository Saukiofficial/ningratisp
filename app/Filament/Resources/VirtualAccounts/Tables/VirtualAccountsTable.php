<?php

namespace App\Filament\Resources\VirtualAccounts\Tables;

use App\Models\VirtualAccount;
use App\Services\Customer\VirtualAccountStatusServices;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class VirtualAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_number')
                    ->label('Invoice Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoice.customerPackage.customer.username')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('va_number')
                    ->label('VA Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('paymentMethod.name')
                    ->label('Payment Method')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('fee_amount')
                    ->label('Fee')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => VirtualAccount::STATUS_SETTLEMENT,
                        'info' => VirtualAccount::STATUS_PENDING,
                        'danger' => [
                            VirtualAccount::STATUS_CANCEL,
                            VirtualAccount::STATUS_EXPIRE,
                            VirtualAccount::STATUS_DENY,
                        ],
                    ])
                    ->sortable(),
                TextColumn::make('expired_at')
                    ->label('Expired At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        VirtualAccount::STATUS_PENDING => 'Pending',
                        VirtualAccount::STATUS_SETTLEMENT => 'Settlement',
                        VirtualAccount::STATUS_EXPIRE => 'Expire',
                        VirtualAccount::STATUS_CANCEL => 'Cancel',
                        VirtualAccount::STATUS_DENY => 'Deny',
                    ]),
                SelectFilter::make('payment_method_id')
                    ->label('Payment Method')
                    ->relationship('paymentMethod', 'name'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Created From'),
                        DatePicker::make('created_until')->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Created from: '.Date::parse($data['created_from'])->format('d F Y'))
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Created until: '.Date::parse($data['created_until'])->format('d F Y'))
                                ->removeField('created_until');
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('check_status')
                        ->label('Sync Status')
                        ->icon(Heroicon::ArrowPath)
                        ->color('info')
                        ->visible(fn (VirtualAccount $record) => $record->status === VirtualAccount::STATUS_PENDING)
                        ->action(function (VirtualAccount $record) {
                            try {
                                $service = app(VirtualAccountStatusServices::class);
                                $result = $service->syncStatus($record);
                                Notification::make()
                                    ->title('Status Synced')
                                    ->body($result['message'])
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Sync Failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Action::make('cancel_va')
                        ->label('Cancel VA')
                        ->icon(Heroicon::XCircle)
                        ->color('warning')
                        ->visible(fn (VirtualAccount $record) => $record->status === VirtualAccount::STATUS_PENDING)
                        ->requiresConfirmation()
                        ->action(function (VirtualAccount $record) {
                            try {
                                $service = app(VirtualAccountStatusServices::class);
                                $result = $service->cancel($record);
                                Notification::make()
                                    ->title('VA Cancelled')
                                    ->body($result['message'])
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Cancellation Failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Action::make('delete')
                        ->label('Delete')
                        ->icon(Heroicon::Trash)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (VirtualAccount $record) {
                            if ($record->status === VirtualAccount::STATUS_PENDING) {
                                try {
                                    $service = app(VirtualAccountStatusServices::class);
                                    $service->cancel($record);
                                } catch (\Exception $e) {
                                    Log::warning('Failed to cancel VA during deletion: '.$e->getMessage());
                                }
                            }
                            $record->delete();
                            Notification::make()
                                ->title('Deleted')
                                ->body('Virtual Account record deleted successfully.')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
