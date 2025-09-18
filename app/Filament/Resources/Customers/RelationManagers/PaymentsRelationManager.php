<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Models\Payment;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments';

    protected function getTableQuery(): Builder|Relation|null
    {
        // Get all invoice IDs for the current customer through their packages.
        $invoiceIds = $this->getOwnerRecord()->invoices()->pluck('invoices.id');

        // From those invoices, get all the unique payment IDs from the allocation pivot table.
        $paymentIds = \App\Models\PaymentAllocation::whereIn('invoice_id', $invoiceIds)
            ->pluck('payment_id')
            ->unique();

        // Return a query builder for the Payment model constrained to the retrieved IDs.
        return Payment::query()->whereIn('id', $paymentIds);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_id')
                    ->label('Reference Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_datetime')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->label('Method'),
                Tables\Columns\TextColumn::make('allocations_count')
                    ->counts('allocations')
                    ->label('Invoices Paid'),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Attachment'),
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Proof'),
            ]);
    }
}
