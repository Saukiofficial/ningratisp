<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Models\Invoices;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\CreditService;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\ReceivableService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customerPackage.customer.customer_name')
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
                    ->options(Payment::getStatusLabel()),
                SelectFilter::make('status')
                    ->options(Invoices::getStatusLabel())
                    ->default(Invoices::STATUS_UNPAID),
                Filter::make('invoice_date')
                    ->schema([
                        DatePicker::make('start_date'),
                        DatePicker::make('end_date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('invoice_date', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('invoice_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['start_date'] ?? null) {
                            $indicators[] = Indicator::make('Start: ' . Date::parse($data['start_date'])->format('d F Y'))
                                ->removeField('from');
                        }

                        if ($data['end_date'] ?? null) {
                            $indicators[] = Indicator::make('Until: ' . Date::parse($data['end_date'])->format('d F Y'))
                                ->removeField('until');
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([

                    Action::make('record_payment')
                        ->label('Record Payment')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Section::make([
                                TextInput::make('customer')
                                    ->disabled()
                                    ->default(
                                        fn(Invoices $record) => $record->customerPackage->customer->username
                                    ),
                                TextInput::make('package')
                                    ->disabled()
                                    ->default(
                                        fn(Invoices $record) => "{$record->customerPackage->package->name} ({$record->customerPackage->package->pppProfile->profile_name})"
                                    ),
                                TextInput::make('invoice')
                                    ->disabled()
                                    ->default(
                                        fn(Invoices $record) => $record->invoice_number
                                    ),
                                TextInput::make('date')
                                    ->disabled()
                                    ->default(
                                        fn(Invoices $record) => $record->invoice_date->format('d F Y')
                                    ),
                            ])
                                ->label('Detail Information')
                                ->columns(),
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
                                DateTimePicker::make('payment_datetime')
                                    ->default(now()),
                                Select::make('payment_method_id')
                                    ->label('Payment Method')
                                    ->options(PaymentMethod::query()->whereHas('fee')->orderBy('name')->pluck('name', 'id')->toArray())
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
                            ])->columns()
                                ->label('Record Payment'),
                            FileUpload::make('file_path')
                                ->label('Payment Struct')
                                // ->required()
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                    'application/pdf',
                                ])
                                ->storeFileNamesIn('file_name')
                                ->visibility('public')
                                ->directory('public/payment-struct'),
                        ])
                        ->action(function (array $data, Invoices $record) {
                            $customer = $record->customerPackage?->customer;
                            if (! $customer) {
                                Notification::make()
                                    ->title('No customer found for this invoice')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $method = null;
                            if (! empty($data['payment_method_id'])) {
                                $method = PaymentMethod::find($data['payment_method_id']);
                            }

                            app(PaymentService::class)->recordIncomingPaymentWithAllocations(
                                $customer,
                                (float) ($data['amount'] ?? 0),
                                $method,
                                $data['reference_id'] ?? null,
                                $record,
                                $data['file_path'],
                                $data['file_name'],
                                datetime: $data['payment_datetime']
                            );

                            $record->refresh();
                            app(ReceivableService::class)->syncForInvoice($record);

                            Notification::make()
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
                                    $available = $customer ? app(CreditService::class)->getBalance($customer) : 0.0;

                                    return min($balance, $available);
                                })
                                ->required()
                                ->default(function (Invoices $record) {
                                    $balance = (float) ($record->balance_due ?? 0);
                                    $customer = $record->customerPackage?->customer;
                                    $available = $customer ? app(CreditService::class)->getBalance($customer) : 0.0;

                                    return min($balance, $available);
                                })
                                ->helperText(function (Invoices $record) {
                                    $customer = $record->customerPackage?->customer;
                                    $available = $customer ? app(CreditService::class)->getBalance($customer) : 0.0;
                                    $balance = (float) ($record->balance_due ?? 0);

                                    return 'Available credit: IDR ' . number_format($available, 2) . ' | Balance due: IDR ' . number_format($balance, 2);
                                }),
                        ])
                        ->action(function (Invoices $record, array $data) {
                            $customer = $record->customerPackage?->customer;
                            if (! $customer) {
                                Notification::make()
                                    ->title('No customer found for this invoice')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $res = app(CreditService::class)
                                ->applyCreditToInvoice($customer, $record, (float) ($data['amount'] ?? 0));
                            $record->refresh();
                            app(ReceivableService::class)->syncForInvoice($record);

                            Notification::make()
                                ->title('Credit applied: ' . number_format((float) ($res['applied'] ?? 0), 2))
                                ->success()
                                ->send();
                        })
                        ->visible(false),
                    // ->visible(fn(Invoices $record) => $record->status !== Invoices::STATUS_CANCELLED && (float) ($record->balance_due ?? 0) > 0),
                    Action::make('adjustment')
                        ->label('Adjustment')
                        ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                        ->schema([
                            Section::make('Invoice Adjustment')
                                ->description('Adjust the total amount of this invoice by adding an adjustment item.')
                                ->schema([
                                    TextInput::make('invoice_number')
                                        ->label('Invoice Number')
                                        ->disabled()
                                        ->default(fn(Invoices $record) => $record->invoice_number),
                                    TextInput::make('customer')
                                        ->label('Invoice Number')
                                        ->disabled()
                                        ->default(fn(Invoices $record) => $record->customerPackage->customer->full_name),
                                    TextInput::make('current_total')
                                        ->label('Current Total Amount')
                                        ->disabled()
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->mask(RawJs::make('$money($input)'))
                                        ->stripCharacters(',')
                                        ->default(fn(Invoices $record) => (float) $record->total_amount),
                                    TextInput::make('adjustment_amount')
                                        ->label('Adjustment Nominal')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->mask(RawJs::make('$money($input)'))
                                        ->stripCharacters(',')
                                        ->live(true)
                                        ->required()
                                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                            $current = (float) ($get('current_total') ?? 0);
                                            $adj = (float) ($state ?? 0);
                                            $set('new_total', number_format($current + $adj));
                                        }),
                                    TextInput::make('new_total')
                                        ->label('New Estimated Total')
                                        ->disabled()
                                        ->prefix('Rp')
                                        ->live()
                                        ->dehydrated(false)
                                        ->default(
                                            fn(Invoices $record) => number_format($record->total_amount)
                                        ),
                                    TextInput::make('description')
                                        ->label('Reason/Description')
                                        ->required()
                                        ->columnSpanFull()
                                        ->default('Penyesuaian Tagihan'),
                                ])->columns(2),
                        ])
                        ->action(function (Invoices $record, array $data) {
                            app(InvoiceService::class)->adjustInvoice(
                                $record,
                                (float) ($data['adjustment_amount'] ?? 0),
                                $data['description']
                            );

                            Notification::make()
                                ->title('Invoice adjusted successfully')
                                ->success()
                                ->send();
                        })
                        ->visible(fn(Invoices $record) => $record->status !== Invoices::STATUS_CANCELLED && $record->status !== Invoices::STATUS_PAID),
                    DeleteAction::make()
                        ->visible(
                            fn(Invoices $record) => $record->status == Invoices::STATUS_UNPAID
                        )
                        ->action(function (Invoices $record): void {
                            $no = $record->invoice_number;
                            DB::beginTransaction();
                            $ok = true;

                            if ($record->payments()->exists()) {
                                $ok = $record->payments()->delete();
                            }

                            if ($ok) {
                                $ok = $record->delete();
                            }

                            if (! $ok) {
                                Notification::make('')
                                    ->title('Action failed')
                                    ->body("Delete Invoice {$no} Fail")
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $ok ? DB::commit() : DB::rollBack();

                            Notification::make('')
                                ->title('Action success')
                                ->body("Invoice {$no} deleted")
                                ->success()
                                ->send();
                        }),
                ]),
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
