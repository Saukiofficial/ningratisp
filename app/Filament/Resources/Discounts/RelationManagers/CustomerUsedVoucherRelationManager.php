<?php

namespace App\Filament\Resources\Discounts\RelationManagers;

use App\Models\Customer;
use App\Models\CustomerDiscount;
use App\Models\Discount;
use App\Models\Invoices;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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

    protected function getNominalsForCustomer(Customer $record): array
    {
        /** @var Discount $discount */
        $discount = $this->getOwnerRecord();
        $discountId = $discount->id;

        // Check if an invoice is linked to this discount and customer
        $invoice = $record->invoices()
            ->where('invoices.discount_id', $discountId)
            ->latest('invoices.created_at')
            ->first();

        if ($invoice) {
            $before = (float) ($invoice->subtotal > 0 ? $invoice->subtotal : $invoice->amount);
            $discountAmount = (float) $invoice->discount_amount;
            $after = (float) $invoice->total_amount;

            return [
                'before' => $before,
                'discount_amount' => $discountAmount,
                'after' => $after,
                'invoice_number' => $invoice->invoice_number,
                'invoice_status' => $invoice->status,
                'invoice_date' => $invoice->invoice_date?->format('d F Y'),
                'payment_status' => Invoices::getPaymentStatusLabel()[$invoice->payment_status ?? $invoice->status] ?? $invoice->status,
                'has_invoice' => true,
            ];
        }

        // Fallback: estimate nominal from customer's active package price
        $packagePrice = (float) ($record->activePackage?->package?->price
            ?? $record->latestCustomerPackage?->package?->price
            ?? 0);

        $before = $packagePrice;
        $discountAmount = 0.0;

        if ($before > 0) {
            if ($discount->type === Discount::PERCENTAGE) {
                $discountAmount = ($before * (float) $discount->value) / 100;
                $maxCap = (float) $discount->max_discount_amount;
                if ($maxCap > 0 && $discountAmount > $maxCap) {
                    $discountAmount = $maxCap;
                }
            } elseif ($discount->type === Discount::FIXED_AMOUNT) {
                $discountAmount = (float) $discount->value;
            }
            $discountAmount = min($before, $discountAmount);
        }

        $after = max(0.0, $before - $discountAmount);

        return [
            'before' => $before,
            'discount_amount' => $discountAmount,
            'after' => $after,
            'invoice_number' => null,
            'invoice_status' => 'Belum Di-generate',
            'invoice_date' => null,
            'payment_status' => 'Belum Dipakai',
            'has_invoice' => false,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
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
                    ->state(fn (Customer $record): string => $this->isVoucherUsedForPayment($record) ? 'Used for Payment' : 'Claimed (Unpaid)')
                    ->color(fn (string $state): string => $state === 'Used for Payment' ? 'success' : 'warning')
                    ->icon(fn (string $state): string => $state === 'Used for Payment' ? 'heroicon-m-check-circle' : 'heroicon-m-clock'),
                TextColumn::make('nominal')
                    ->label('Nominal')
                    ->html()
                    ->state(function (Customer $record): string {
                        $n = $this->getNominalsForCustomer($record);
                        $beforeFormatted = 'Rp '.number_format($n['before'], 0, ',', '.');
                        $discountFormatted = '-Rp '.number_format($n['discount_amount'], 0, ',', '.');
                        $afterFormatted = 'Rp '.number_format($n['after'], 0, ',', '.');

                        return "
                            <div style='display: flex; flex-direction: column; gap: 2px;'>
                                <div style='font-weight: 700; font-size: 13px; color: #059669;' class='dark:text-emerald-400'>
                                    {$afterFormatted}
                                </div>
                                <div style='display: flex; align-items: center; gap: 6px; font-size: 11px; color: #6b7280;' class='dark:text-gray-400'>
                                    <span style='text-decoration: line-through;'>{$beforeFormatted}</span>
                                    <span style='color: #e11d48; font-weight: 600;' class='dark:text-rose-400'>({$discountFormatted})</span>
                                </div>
                            </div>
                        ";
                    }),
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
                Action::make('viewNominals')
                    ->label('Rincian Nominal')
                    ->button()
                    ->color('info')
                    ->icon('heroicon-m-banknotes')
                    ->modalHeading('Rincian Nominal Diskon & Pembayaran')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->infolist(function (Customer $record): array {
                        $nominals = $this->getNominalsForCustomer($record);
                        $discount = $this->getOwnerRecord();
                        $isUsed = $this->isVoucherUsedForPayment($record);

                        return [
                            Section::make('Informasi Pelanggan & Voucher')
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextEntry::make('customer_name')
                                            ->label('Nama Pelanggan')
                                            ->state($record->customer_name ?? $record->full_name)
                                            ->weight('bold'),
                                        TextEntry::make('username')
                                            ->label('Username')
                                            ->state($record->username)
                                            ->copyable(),
                                        TextEntry::make('payment_status_label')
                                            ->label('Status Penggunaan')
                                            ->state($isUsed ? 'Used for Payment' : 'Claimed (Unpaid)')
                                            ->badge()
                                            ->color($isUsed ? 'success' : 'warning')
                                            ->icon($isUsed ? 'heroicon-m-check-circle' : 'heroicon-m-clock'),
                                    ]),
                                    Grid::make(2)->schema([
                                        TextEntry::make('discount_name')
                                            ->label('Nama Diskon / Voucher')
                                            ->state($discount->name),
                                        TextEntry::make('discount_code')
                                            ->label('Kode Diskon')
                                            ->state($discount->code)
                                            ->copyable()
                                            ->color('primary')
                                            ->weight('bold'),
                                    ]),
                                ]),

                            Section::make('Rincian Perhitungan Nominal')
                                ->description('Perbandingan harga sebelum dan sesudah penerapan potongan diskon')
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextEntry::make('nominal_before')
                                            ->label('Harga Sebelum Diskon')
                                            ->state($nominals['before'])
                                            ->money('IDR')
                                            ->color('gray'),
                                        TextEntry::make('discount_deduction')
                                            ->label('Potongan Diskon')
                                            ->state($nominals['discount_amount'])
                                            ->money('IDR')
                                            ->color('danger')
                                            ->weight('semibold'),
                                        TextEntry::make('nominal_after')
                                            ->label('Harga Sesudah (Total Bayar)')
                                            ->state($nominals['after'])
                                            ->money('IDR')
                                            ->color('success')
                                            ->weight('bold'),
                                    ]),
                                ]),

                            Section::make('Informasi Tagihan Terkait')
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextEntry::make('invoice_number')
                                            ->label('No. Invoice')
                                            ->state($nominals['invoice_number'] ?? 'Belum Di-generate')
                                            ->placeholder('-'),
                                        TextEntry::make('invoice_date')
                                            ->label('Tanggal Invoice')
                                            ->state($nominals['invoice_date'] ?? '-')
                                            ->placeholder('-'),
                                        TextEntry::make('invoice_payment_status')
                                            ->label('Status Bayar Invoice')
                                            ->state($nominals['payment_status'] ?? '-')
                                            ->badge()
                                            ->color(fn ($state) => match (strtolower((string) $state)) {
                                                'lunas', 'paid', 'sudah bayar' => 'success',
                                                'sebagian', 'partial' => 'warning',
                                                default => 'gray',
                                            }),
                                    ]),
                                ])
                                ->visible(fn (): bool => (bool) $nominals['has_invoice']),
                        ];
                    }),
                DetachAction::make('detach')
                    ->label('Remove Claim')
                    ->button()
                    ->color('danger')
                    ->icon('heroicon-m-trash')
                    ->modalHeading('Remove Claimed Voucher')
                    ->modalDescription('Are you sure you want to remove this claimed voucher from this customer? This will free up the voucher quota.')
                    ->visible(fn (Customer $record): bool => ! $this->isVoucherUsedForPayment($record))
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
