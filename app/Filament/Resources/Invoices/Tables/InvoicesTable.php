<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Models\Invoices;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\RawJs;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customerPackage.customer.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('discount_amount')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('balance_due')
                    ->label('Remaining')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'success' => Payment::STATUS_PAID,
                        'info' => Payment::STATUS_PARTIAL,
                        'danger' => Payment::STATUS_UNPAID,
                    ])
                    ->formatStateUsing(fn(string $state) => Payment::getStatusLabel()[$state]),
                TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options(Payment::getStatusLabel())
            ])
            ->recordActions([
                ActionGroup::make([

                    Action::make('record_payment')
                        ->label('Record Payment')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make([
                                TextInput::make('amount')
                                    ->label('Amount')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0.01)
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->maxValue(fn(Invoices $record) => (float) ($record->balance_due ?? 0))
                                    ->required()
                                    ->default(fn(Invoices $record) => (float) ($record->balance_due ?? 0)),
                                Select::make('payment_method_id')
                                    ->label('Payment Method')
                                    ->options(\App\Models\PaymentMethod::query()->whereHas('fee')->orderBy('name')->pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default(
                                        PaymentMethod::query()->where('code', 'cash')->first()?->id ?? null
                                    )
                                    ->disabled()
                                    ->dehydrated(),
                                TextInput::make('reference_id')
                                    ->label('Reference')
                                    ->disabled()
                                    ->dehydrated()
                                    ->default('MAN-' . now()->unix()),
                            ])->columns(3),
                            FileUpload::make('file_path')
                                ->label('Payment Struct')
                                ->required()
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                    'application/pdf',
                                ])
                                ->storeFileNamesIn('file_name')
                                ->visibility('public')
                                ->directory('public/payment-struct')
                        ])
                        ->action(function (array $data, Invoices $record) {
                            $customer = $record->customerPackage?->customer;
                            if (!$customer) {
                                \Filament\Notifications\Notification::make()
                                    ->title('No customer found for this invoice')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $method = null;
                            if (!empty($data['payment_method_id'])) {
                                $method = \App\Models\PaymentMethod::find($data['payment_method_id']);
                            }


                            app(\App\Services\PaymentService::class)->recordIncomingPaymentWithAllocations(
                                $customer,
                                (float) ($data['amount'] ?? 0),
                                $method,
                                $data['reference_id'] ?? null,
                                $record,
                                $data['file_path'],
                                $data['file_name']
                            );

                            $record->refresh();
                            app(\App\Services\ReceivableService::class)->syncForInvoice($record);

                            \Filament\Notifications\Notification::make()
                                ->title('Payment recorded')
                                ->success()
                                ->send();
                        })
                        ->visible(
                            fn(Invoices $record) => $record->status !== Invoices::STATUS_CANCELLED
                                && (float) ($record->balance_due ?? 0) > 0
                        ),

                    Action::make('apply_credit')
                        ->label('Apply Credit')
                        ->icon('heroicon-o-credit-card')
                        ->schema([
                            TextInput::make('amount')
                                ->label('Amount to apply')
                                ->numeric()
                                ->minValue(0.01)
                                ->maxValue(function (Invoices $record) {
                                    $balance = (float) ($record->balance_due ?? 0);
                                    $customer = $record->customerPackage?->customer;
                                    $available = $customer ? app(\App\Services\CreditService::class)->getBalance($customer) : 0.0;
                                    return min($balance, $available);
                                })
                                ->required()
                                ->default(function (Invoices $record) {
                                    $balance = (float) ($record->balance_due ?? 0);
                                    $customer = $record->customerPackage?->customer;
                                    $available = $customer ? app(\App\Services\CreditService::class)->getBalance($customer) : 0.0;
                                    return min($balance, $available);
                                })
                                ->helperText(function (Invoices $record) {
                                    $customer = $record->customerPackage?->customer;
                                    $available = $customer ? app(\App\Services\CreditService::class)->getBalance($customer) : 0.0;
                                    $balance = (float) ($record->balance_due ?? 0);
                                    return 'Available credit: IDR ' . number_format($available, 2) . ' | Balance due: IDR ' . number_format($balance, 2);
                                }),
                        ])
                        ->action(function (Invoices $record, array $data) {
                            $customer = $record->customerPackage?->customer;
                            if (!$customer) {
                                \Filament\Notifications\Notification::make()
                                    ->title('No customer found for this invoice')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $res = app(\App\Services\CreditService::class)
                                ->applyCreditToInvoice($customer, $record, (float) ($data['amount'] ?? 0));
                            $record->refresh();
                            app(\App\Services\ReceivableService::class)->syncForInvoice($record);

                            \Filament\Notifications\Notification::make()
                                ->title('Credit applied: ' . number_format((float) ($res['applied'] ?? 0), 2))
                                ->success()
                                ->send();
                        })
                        ->visible(false),
                    // ->visible(fn(Invoices $record) => $record->status !== Invoices::STATUS_CANCELLED && (float) ($record->balance_due ?? 0) > 0),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn(Invoices $record) => $record->status == Invoices::STATUS_UNPAID
            )
            ->defaultSort('updated_at', 'desc');
    }
}
