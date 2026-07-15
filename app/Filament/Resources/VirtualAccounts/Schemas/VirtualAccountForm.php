<?php

namespace App\Filament\Resources\VirtualAccounts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VirtualAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Virtual Account Information')
                    ->columns(2)
                    ->components([
                        TextInput::make('va_number')
                            ->label('VA Number')
                            ->disabled(),
                        TextInput::make('status')
                            ->label('Status')
                            ->disabled(),
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('Rp ')
                            ->disabled(),
                        TextInput::make('fee_amount')
                            ->label('Fee Amount')
                            ->numeric()
                            ->prefix('Rp ')
                            ->disabled(),
                        DateTimePicker::make('expired_at')
                            ->label('Expired At')
                            ->disabled(),
                        DateTimePicker::make('created_at')
                            ->label('Created At')
                            ->disabled(),
                    ]),
                Section::make('Related Reference Information')
                    ->columns(2)
                    ->components([
                        TextInput::make('invoice.invoice_number')
                            ->label('Invoice Number')
                            ->disabled(),
                        TextInput::make('invoice.customerPackage.customer.customer_name')
                            ->label('Customer Name')
                            ->disabled(),
                        TextInput::make('paymentMethod.name')
                            ->label('Payment Method')
                            ->disabled(),
                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->disabled(),
                        TextInput::make('order_id')
                            ->label('Order ID')
                            ->disabled(),
                    ]),
            ]);
    }
}
