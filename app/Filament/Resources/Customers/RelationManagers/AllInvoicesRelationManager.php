<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class AllInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'All Invoices';

    // public function getRelationship()
    // {
    //     $customer = $this->getOwnerRecord();
    //     return \App\Models\Invoices::query()
    //         ->whereHas('customerPackage', function (Builder $query) use ($customer) {
    //             $query->where('customer_id', $customer->id);
    //         });
    // }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number'),
                Tables\Columns\TextColumn::make('total_amount')->money('IDR'),
                Tables\Columns\TextColumn::make('due_date')->date(),
                Tables\Columns\TextColumn::make('status'),
            ]);
    }
}
