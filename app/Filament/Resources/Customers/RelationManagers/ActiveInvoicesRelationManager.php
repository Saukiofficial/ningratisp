<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Models\CustomerPackages;
use App\Models\Invoices;
use App\Models\PaymentMethod;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ActiveInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Active Invoices';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query->where($this->getRelationshipName() . '.status', Invoices::STATUS_UNPAID)
            )
            ->columns([
                Tables\Columns\TextColumn::make('customerPackage.package.name'),
                Tables\Columns\TextColumn::make('invoice_number'),
                Tables\Columns\TextColumn::make('total_amount')->money('IDR'),
                Tables\Columns\TextColumn::make('discount_amount')->money('IDR'),
                Tables\Columns\TextColumn::make('paid_amount')->money('IDR'),
                Tables\Columns\TextColumn::make('due_date')->date(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('record_payment')
                        ->icon(Heroicon::OutlinedBanknotes)
                        ->visible(
                            fn(Invoices $record) => $record->status != Invoices::STATUS_PAID
                        )
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
                                    ->options(
                                        PaymentMethod::query()->whereHas('fee')->orderBy('name')->pluck('name', 'id')->toArray()
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default(
                                        PaymentMethod::query()->where('code', 'cash')->firstOrFail()->id
                                    ),
                                TextInput::make('reference_id')
                                    ->label('Reference')
                                    ->maxLength(100)
                                    ->required(),
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
                                ->directory('public/payment-struct'),
                        ])
                        ->action(function (array $data, Invoices $record) {
                            $customer = $record->customerPackage?->customer;
                            if (! $customer) {
                                \Filament\Notifications\Notification::make()
                                    ->title('No customer found for this invoice')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $method = null;
                            if (! empty($data['payment_method_id'])) {
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
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkAction::make('pay')
                    ->label('Pay Selected')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->schema([
                        Section::make([
                            TextInput::make('amount')
                                ->label('Amount')
                                ->numeric()
                                ->prefix('Rp')
                                ->minValue(0.01)
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->maxValue(
                                    fn(RelationManager $livewire) => $livewire->getSelectedTableRecords()->sum('balance_due')
                                )
                                ->required()
                                ->default(
                                    fn(RelationManager $livewire) => $livewire->getSelectedTableRecords()->sum('balance_due')
                                ),
                            Select::make('payment_method_id')
                                ->label('Payment Method')
                                ->options(
                                    PaymentMethod::query()->whereHas('fee')->orderBy('name')->pluck('name', 'id')->toArray()
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->default(
                                    PaymentMethod::query()->where('code', 'cash')->firstOrFail()->id
                                )
                                ->disabled()
                                ->dehydrated(),
                            TextInput::make('reference_id')
                                ->label('Reference')
                                ->maxLength(100)
                                ->required(),
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
                            ->directory('public/payment-struct'),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $customer = $this->getOwnerRecord();
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
                        $invoiceIds = $records->pluck('id')->toArray();

                        app(\App\Services\PaymentService::class)->recordIncomingPaymentWithAllocations(
                            $customer,
                            (float) ($data['amount'] ?? 0),
                            $method,
                            $data['reference_id'] ?? null,
                            invoiceIds: $invoiceIds,
                            filePath: $data['file_path'],
                            fileName: $data['file_name']
                        );

                        app(\App\Services\ReceivableService::class)->syncForCustomer($customer);

                        Notification::make()
                            ->title('Payment recorded')
                            ->success()
                            ->send();
                    }),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn(Invoices $record) => $record->status != Invoices::STATUS_PAID
            )
            ->headerActions([
                Action::make('create_invoice')
                    ->visible(
                        fn(RelationManager $livewire) => $livewire->getOwnerRecord()
                            ->customerPackages()
                            ->where('status', CustomerPackages::STATUS_ACTIVE)
                            ->exists()
                    )
                    ->schema([
                        Select::make('package_id')
                            ->label('Package')
                            ->options(function (RelationManager $livewire) {
                                return $livewire->getOwnerRecord()->customerPackages()
                                    ->with('package')
                                    ->get()
                                    ->mapWithKeys(
                                        fn(CustomerPackages $customerPackage) => [
                                            $customerPackage->package_id => $customerPackage->package->name,
                                        ]
                                    );
                            })
                            ->required()
                            ->default(
                                fn(RelationManager $livewire) => $livewire->getOwnerRecord()->activePackage->package_id ?? null
                            ),
                        DatePicker::make('due_date')
                            ->required()
                            ->default(now()),
                        TextInput::make('batch')
                            ->integer()
                            ->label('Total Invoice')
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(12),
                    ])
                    ->action(function (array $data) {
                        $customerPackageId = CustomerPackages::query()->where([
                            'package_id' => $data['package_id'],
                            'customer_id' => $this->getOwnerRecord()->id,
                        ])
                            ->firstOrFail('id');

                        $this->addInvoice(
                            $customerPackageId->id,
                            $data['due_date'],
                            $data['batch']
                        );
                    }),
            ]);
    }

    protected function addInvoice(int $packageId, $dueDate, $batch = 1)
    {
        try {
            /** @var \App\Services\InvoiceService $invoiceService */
            $invoiceService = app(\App\Services\InvoiceService::class);
            $invoiceService->createManualInvoicesForPackage($packageId, $dueDate, $batch);

            Notification::make()
                ->title('Invoices created successfully')
                ->body("Successfully created {$batch} invoice(s).")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('An error occurred')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
