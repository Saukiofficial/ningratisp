<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Models\CustomerPackages;
use App\Models\Invoices;
use App\Models\PaymentMethod;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\ReceivableService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DetachAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ActiveInvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Active Invoices';

    public function table(Table $table): Table
    {
        /** @var \App\Models\User */
        $user = auth('web')->user();

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
                                    ->options(
                                        PaymentMethod::query()->whereHas('fee')->orderBy('name')->pluck('name', 'id')->toArray()
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default(
                                        PaymentMethod::query()->where('code', 'cash')->first()?->id ?? null
                                    ),
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
                                $data['file_name'],
                                datetime: $data['payment_datetime']
                            );

                            $record->refresh();
                            app(\App\Services\ReceivableService::class)->syncForInvoice($record);

                            \Filament\Notifications\Notification::make()
                                ->title('Payment recorded')
                                ->success()
                                ->send();
                        }),
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
                                        ->afterStateUpdated(function ($get, $set, $state) {
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
                    Action::make('delete')
                        ->modalHeading(
                            fn(): string => __('filament-actions::delete.single.modal.heading', ['label' => $this->getRelationshipTitle()])
                        )
                        ->visible(
                            fn(Invoices $record) => $user->can('delete', $record) &&  $record->status == Invoices::STATUS_UNPAID
                        )
                        ->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'))
                        ->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'))
                        ->defaultColor('danger')
                        ->tableIcon(Heroicon::Trash)
                        ->groupedIcon(Heroicon::Trash)
                        ->requiresConfirmation()
                        ->modalIcon(Heroicon::OutlinedTrash)
                        ->modalWidth(Width::ScreenLarge)
                        ->schema([
                            Section::make(fn(Invoices $record) => 'Invoice : ' . $record->invoice_number)
                                ->columns()
                                ->components([
                                    TextInput::make('package')
                                        ->default(
                                            fn(Invoices $record) => $record->customerPackage->package->name
                                        )
                                        ->disabled(),
                                    TextInput::make('date')
                                        ->default(fn(Invoices $record) => $record->invoice_date->format('d F Y'))
                                        ->disabled(),
                                    TextInput::make('total_amount')
                                        ->default(
                                            fn(Invoices $record) => 'Rp. ' . number_format($record->total_amount, 2, ',', '.')
                                        )
                                        ->disabled(),
                                    TextInput::make('paid_amount')
                                        ->default(
                                            fn(Invoices $record) => 'Rp. ' . number_format($record->paid_amount, 2, ',', '.')
                                        )
                                        ->disabled(),
                                ]),
                            // Repeater::make('allocations')
                            //     ->relationship()
                            //     ->compact()
                            //     ->table([
                            //         TableColumn::make('total_amount'),
                            //         TableColumn::make('allocated_at'),
                            //     ])
                            //     ->schema([
                            //         TextInput::make('total_amount')->disabled(),
                            //         TextInput::make('allocated_at')->disabled(),
                            //     ])
                            //     ->deletable(false)
                            //     ->addable(false)
                        ])
                        ->keyBindings(['mod+d'])
                        ->hidden(static function (Invoices $record): bool {
                            if (! method_exists($record, 'trashed')) {
                                return false;
                            }

                            return $record->trashed();
                        })

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
                        })
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
                                    PaymentMethod::query()->where('code', 'cash')->first()?->id ?? null
                                )
                                ->disabled()
                                ->dehydrated(),
                            TextInput::make('reference_id')
                                ->label('Reference')
                                ->disabled()
                                ->dehydrated()
                                ->default('MAN-' . now()->unix()),
                        ])->columns(3),
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

                        app(PaymentService::class)->recordIncomingPaymentWithAllocations(
                            $customer,
                            (float) ($data['amount'] ?? 0),
                            $method,
                            $data['reference_id'] ?? null,
                            invoiceIds: $invoiceIds,
                            filePath: $data['file_path'],
                            fileName: $data['file_name']
                        );

                        app(ReceivableService::class)->syncForCustomer($customer);

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
                        fn(RelationManager $livewire) => $user->can('Create:Invoices') && $livewire->getOwnerRecord()
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
