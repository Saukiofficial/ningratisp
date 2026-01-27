<?php

namespace App\Filament\Resources\Invoices\RelationManagers;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AllocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'allocations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('payment_id')
                    ->label('Payment')
                    ->relationship('payment', 'reference_id')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
                DateTimePicker::make('allocated_at'),
                Textarea::make('notes')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('notes')
            ->columns([
                TextColumn::make('payment.reference_id')
                    ->label('Reference')
                    ->searchable(),
                TextColumn::make('payment.total_amount')
                    ->label('Payment')
                    ->money('IDR')
                    ->alignRight(),
                TextColumn::make('amount')
                    ->money('IDR')
                    ->alignRight(),
                TextColumn::make('allocated_at')
                    ->dateTime(),
                TextColumn::make('notes')
                    ->limit(50)
                    ->wrap(),
            ])
            ->headerActions([
                Action::make('record_payment')
                    ->label('Record Payment')
                    ->icon('heroicon-o-banknotes')
                    ->form([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->default(fn() => (float) (($this->getOwnerRecord()->balance_due ?? 0))),
                        Select::make('payment_method_id')
                            ->label('Payment Method')
                            ->options(\App\Models\PaymentMethod::query()->orderBy('name')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload(),
                        TextInput::make('reference_id')
                            ->label('Reference')
                            ->maxLength(100),
                    ])
                    ->action(function (array $data, \App\Services\PaymentService $payments) {
                        $invoice = $this->getOwnerRecord();
                        $customer = $invoice->customerPackage?->customer;
                        if (!$customer) {
                            Notification::make()
                                ->title('No customer found for this invoice')
                                ->danger()
                                ->send();
                            return;
                        }

                        $method = null;
                        if (!empty($data['payment_method_id'])) {
                            $method = \App\Models\PaymentMethod::find($data['payment_method_id']);
                        }

                        $payments->recordIncomingPayment(
                            $customer,
                            (float) ($data['amount'] ?? 0),
                            $method,
                            $data['reference_id'] ?? null,
                            $invoice
                        );

                        $invoice->refresh();

                        Notification::make()
                            ->title('Payment recorded')
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => $this->getOwnerRecord()->status !== \App\Models\Invoices::STATUS_CANCELLED && (float) (($this->getOwnerRecord()->balance_due ?? 0)) > 0),

                Action::make('apply_credit')
                    ->label('Apply Credit')
                    ->icon('heroicon-o-credit-card')
                    ->form([
                        TextInput::make('amount')
                            ->label('Amount to apply')
                            ->numeric()
                            ->required()
                            ->default(fn() => (float) (($this->getOwnerRecord()->balance_due ?? 0))),
                    ])
                    ->action(function (array $data, \App\Services\CreditService $credit) {
                        $invoice = $this->getOwnerRecord();
                        $customer = $invoice->customerPackage?->customer;
                        if (!$customer) {
                            Notification::make()
                                ->title('No customer found for this invoice')
                                ->danger()
                                ->send();
                            return;
                        }

                        $res = $credit->applyCreditToInvoice($customer, $invoice, (float) ($data['amount'] ?? 0));
                        $invoice->refresh();

                        Notification::make()
                            ->title('Credit applied: ' . number_format((float) ($res['applied'] ?? 0), 2))
                            ->success()
                            ->send();
                    })
                    ->visible(fn() => $this->getOwnerRecord()->status !== \App\Models\Invoices::STATUS_CANCELLED && (float) (($this->getOwnerRecord()->balance_due ?? 0)) > 0),
            ])
            ->defaultSort('id', 'desc');
    }
}
