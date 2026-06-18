<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Models\Invoices;
use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
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
                Tables\Columns\TextColumn::make('invoice_date')->date(),
                Tables\Columns\TextColumn::make('total_amount')->money('IDR'),
                Tables\Columns\TextColumn::make('due_date')->date(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->recordActions([
                Action::make('cancel_payment')
                    ->icon(Heroicon::XMark)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalWidth(Width::ScreenLarge)
                    ->schema([
                        Section::make([
                            TextInput::make('invoice_number')
                                ->disabled()
                                ->default(
                                    fn(Invoices $record) => $record->invoice_number
                                ),
                            TextInput::make('invoice_date')
                                ->disabled()
                                ->default(
                                    fn(Invoices $record) => $record->invoice_date->format('d F Y')
                                ),
                            TextInput::make('reference_id')
                                ->disabled()
                                ->default(
                                    fn(Invoices $record) => $record->payments->map(
                                        fn(Payment $payment) => $payment->reference_id
                                    )
                                        ->filter()
                                        ->implode(', ')
                                ),
                            TextInput::make('payment_datetime')
                                ->disabled()
                                ->default(
                                    fn(Invoices $record) => $record->payments->map(
                                        fn(Payment $payment) => $payment->payment_datetime->format('d F Y H:i:s')
                                    )
                                        ->filter()
                                        ->implode(', ')
                                ),
                        ])
                            ->label('Detail Information')
                            ->columns(),
                    ])
                    ->visible(
                        fn(Invoices $record) => $record->payment_status != Payment::STATUS_UNPAID
                    )
                    ->keyBindings(['mod+d'])
                    ->action(
                        function (Invoices $record): void {
                            $state = $record->cancelPayment();
                            $success = $state ? 'Success' : 'Failed';
                            $title = 'Action : ' . $success;
                            $message = "Payment invoice {$record->invoice_number} {$success} deleted";

                            $notification = Notification::make()
                                ->title($title)
                                ->body($message);

                            $state ? $notification->success() : $notification->danger();
                            $notification->send();
                        }
                    )
            ]);
    }
}
