<?php

namespace App\Filament\Resources\Discounts\RelationManagers;

use App\Models\Customer;
use App\Models\CustomerDiscount;
use App\Models\Invoices;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CustomerUsedVoucherRelationManager extends RelationManager
{
    protected static string $relationship = 'customers';

    protected static ?string $title = 'Claimed Vouchers / Customers';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    protected function isVoucherUsedForPayment(Customer $record): bool
    {
        $pivot = $record->pivot;

        if (! $pivot) {
            return false;
        }

        // If pivot is_active is explicitly false (0), it has been used/finalized for payment
        if (isset($pivot->is_active) && ! (bool) $pivot->is_active) {
            return true;
        }

        // If notes references a specific invoice (e.g. "Discount applied to invoice : INV-..."), check if that invoice is paid
        if (! empty($pivot->notes) && preg_match('/INV-[A-Z0-9-]+/', $pivot->notes, $matches)) {
            $invoiceNumber = $matches[0];

            return Invoices::where('invoice_number', $invoiceNumber)
                ->where('status', Invoices::STATUS_PAID)
                ->exists();
        }

        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('customer_name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->state(fn(Customer $record): string => $this->isVoucherUsedForPayment($record) ? 'Used for Payment' : 'Claimed (Unpaid)')
                    ->color(fn(string $state): string => $state === 'Used for Payment' ? 'success' : 'warning')
                    ->icon(fn(string $state): string => $state === 'Used for Payment' ? 'heroicon-m-check-circle' : 'heroicon-m-clock'),
                IconColumn::make('used_for_payment_flag')
                    ->label('Used for Payment')
                    ->boolean()
                    ->state(fn(Customer $record): bool => $this->isVoucherUsedForPayment($record)),
                TextColumn::make('pivot.applied_at')
                    ->label('Claimed At')
                    ->dateTime('d F Y, H:i:s')
                    ->sortable(),
                TextColumn::make('pivot.notes')
                    ->label('Notes')
                    ->searchable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'paid' => 'Used for Payment',
                        'unpaid' => 'Claimed (Unpaid)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $discountId = $this->getOwnerRecord()->id;
                        if (($data['value'] ?? null) === 'paid') {
                            return $query->where(function (Builder $q) use ($discountId) {
                                $q->whereHas('invoices', function (Builder $inv) use ($discountId) {
                                    $inv->where('invoices.discount_id', $discountId)->where('invoices.status', Invoices::STATUS_PAID);
                                })->orWhere('customer_discounts.is_active', false);
                            });
                        }
                        if (($data['value'] ?? null) === 'unpaid') {
                            return $query->where('customer_discounts.is_active', true)
                                ->whereDoesntHave('invoices', function (Builder $inv) use ($discountId) {
                                    $inv->where('invoices.discount_id', $discountId)->where('invoices.status', Invoices::STATUS_PAID);
                                });
                        }

                        return $query;
                    }),
            ])
            ->headerActions([])
            ->recordActions([
                DetachAction::make('detach')
                    ->label('Remove Claim')
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-m-trash')
                    ->modalHeading('Remove Claimed Voucher')
                    ->modalDescription('Are you sure you want to remove this claimed voucher from this customer? This will free up the voucher quota.')
                    ->visible(fn(Customer $record): bool => ! $this->isVoucherUsedForPayment($record))
                    ->after(function (Customer $record): void {
                        $discountId = $this->getOwnerRecord()->id;
                        $record->invoices()
                            ->where('invoices.discount_id', $discountId)
                            ->where('invoices.status', '!=', Invoices::STATUS_PAID)
                            ->each(function (Invoices $invoice) {
                                $invoice->discount_id = null;
                                $invoice->discount_amount = 0;
                                $invoice->recalculateTotals();
                                $invoice->save();
                            });
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->label('Remove Selected Unpaid Claims')
                        ->action(function (Collection $records): void {
                            $discountId = $this->getOwnerRecord()->id;
                            foreach ($records as $record) {
                                if (! $this->isVoucherUsedForPayment($record)) {
                                    if ($record->pivot?->id) {
                                        CustomerDiscount::where('id', $record->pivot->id)->delete();
                                    } else {
                                        $this->getOwnerRecord()->customers()->detach($record->id);
                                    }
                                    $record->invoices()
                                        ->where('invoices.discount_id', $discountId)
                                        ->where('invoices.status', '!=', Invoices::STATUS_PAID)
                                        ->each(function (Invoices $invoice) {
                                            $invoice->discount_id = null;
                                            $invoice->discount_amount = 0;
                                            $invoice->recalculateTotals();
                                            $invoice->save();
                                        });
                                }
                            }
                        }),
                ]),
            ]);
    }
}
