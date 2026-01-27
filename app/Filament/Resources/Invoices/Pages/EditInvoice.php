<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),

            Action::make('record_payment')
                ->label('Record Payment')
                ->icon('heroicon-o-banknotes')
                ->form([
                    TextInput::make('amount')
                        ->label('Amount')
                        ->numeric()
                        ->required()
                        ->default(fn() => (float) ($this->record->balance_due ?? 0)),
                    Select::make('payment_method_id')
                        ->label('Payment Method')
                        ->options(\App\Models\PaymentMethod::query()->orderBy('name')->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->preload(),
                    TextInput::make('reference_id')
                        ->label('Reference')
                        ->maxLength(100),
                ])
                ->action(function (array $data) {
                    $record = $this->record; // Invoices model
                    $customer = $record->customerPackage?->customer;
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

                    app(\App\Services\PaymentService::class)->recordIncomingPayment(
                        $customer,
                        (float) ($data['amount'] ?? 0),
                        $method,
                        $data['reference_id'] ?? null,
                        $record
                    );

                    $this->record->refresh();

                    Notification::make()
                        ->title('Payment recorded')
                        ->success()
                        ->send();
                })
                ->visible(fn() => $this->record->status !== \App\Models\Invoices::STATUS_CANCELLED && (float) ($this->record->balance_due ?? 0) > 0),

            Action::make('apply_credit')
                ->label('Apply Credit')
                ->icon('heroicon-o-credit-card')
                ->form([
                    TextInput::make('amount')
                        ->label('Amount to apply')
                        ->numeric()
                        ->required()
                        ->default(fn() => (float) ($this->record->balance_due ?? 0)),
                ])
                ->action(function (array $data) {
                    $record = $this->record; // Invoices model
                    $customer = $record->customerPackage?->customer;
                    if (!$customer) {
                        Notification::make()
                            ->title('No customer found for this invoice')
                            ->danger()
                            ->send();
                        return;
                    }

                    $res = app(\App\Services\CreditService::class)
                        ->applyCreditToInvoice($customer, $record, (float) ($data['amount'] ?? 0));
                    $this->record->refresh();

                    Notification::make()
                        ->title('Credit applied: ' . number_format((float) ($res['applied'] ?? 0), 2))
                        ->success()
                        ->send();
                })
                ->visible(fn() => $this->record->status !== \App\Models\Invoices::STATUS_CANCELLED && (float) ($this->record->balance_due ?? 0) > 0),
        ];
    }
}
