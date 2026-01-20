<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\CustomerPackages;
use App\Models\Invoices;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Details')
                    ->columns(2)
                    ->components([
                        TextInput::make('invoice_number')
                            ->required()
                            ->maxLength(100),
                        Select::make('customer_package_id')
                            ->label('Customer')
                            ->options(CustomerPackages::all()->pluck('customer.full_name', 'id'))
                            ->searchable()
                            ->required(),
                        DatePicker::make('invoice_date')
                            ->required(),
                        DatePicker::make('due_date'),
                        DatePicker::make('period_start')
                            ->required(),
                        DatePicker::make('period_end'),
                    ]),
                Section::make('Amount Details')
                    ->columns(3)
                    ->components([
                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        TextInput::make('tax_amount')
                            ->numeric()
                            ->default(0),
                        TextInput::make('total_amount')
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Status and Notes')
                    ->columns(1)
                    ->components([
                        Select::make('status')
                            ->options(Invoices::getStatusLabel())
                            ->required(),
                        Textarea::make('notes')
                            ->maxLength(65535),
                    ]),
            ]);
    }
}
