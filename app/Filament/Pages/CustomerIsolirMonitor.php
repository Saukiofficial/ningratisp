<?php

namespace App\Filament\Pages;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Invoices;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerIsolirMonitor extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected string $view = 'filament.pages.customer-isolir-monitor';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignalSlash;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Customer::query()->whereHas('activePackage')
            )
            ->defaultSort('id', 'desc')
            ->filters([
                TernaryFilter::make('auto_isolir')
                    ->default(true),
                TernaryFilter::make('has_invoice')
                    ->label('Has Unpaid Invoice')
                    ->queries(
                        true: fn (Builder $query) => $query->whereHas(
                            'invoices',
                            fn (Builder $q) => $q->where('payment_status', Invoices::STATUS_UNPAID)
                        ),
                        false: fn (Builder $query) => $query->whereDoesntHave(
                            'invoices',
                            fn (Builder $q) => $q->where('payment_status', Invoices::STATUS_UNPAID)
                        ),
                    )->default(),
                TernaryFilter::make('isolir_at')
                    ->label('Isolir')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('isolir_at'),
                        false: fn (Builder $query) => $query->whereNull('isolir_at')
                    )
                    ->default(false),
            ])
            ->columns([
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('remote_address')
                    ->formatStateUsing(
                        fn (Customer $record) => $record->remote_address.' ('.$record->pppProfile->profile_name.')'
                    ),
                IconColumn::make('auto_isolir')
                    ->boolean(),
                TextColumn::make('invoices_count')
                    ->label('Invoice')
                    ->counts([
                        'invoices' => fn (Builder $query) => $query->where('payment_status', Invoices::STATUS_UNPAID),
                    ])
                    ->sortable(),
                IconColumn::make('isolir_at')
                    ->label('Isolir')
                    ->boolean()
                    ->getStateUsing(fn (Customer $record) => ! empty($record->isolir_at))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('isolir_at_date')
                    ->label('Isolir Date')
                    ->getStateUsing(
                        fn (Customer $record) => $record->isolir_at
                    )
                    ->date('d F Y H:i:s')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('isolir_at', $direction);
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('toggle_auto_isolir')
                        ->label('Edit Auto Isolir')
                        ->icon('heroicon-o-pencil-square')
                        ->modalHeading('Edit Auto Isolir')
                        ->modalDescription(fn (Customer $record) => "Update auto isolir setting for {$record->username}")
                        ->modalWidth('sm')
                        ->schema([
                            Toggle::make('auto_isolir')
                                ->label('Auto Isolir')
                                ->onColor('success')
                                ->offColor('danger')
                                ->default(fn (Customer $record) => $record->auto_isolir),
                        ])
                        ->fillForm(fn (Customer $record) => [
                            'auto_isolir' => $record->auto_isolir,
                        ])
                        ->action(function (Customer $record, array $data): void {
                            $record->update(['auto_isolir' => $data['auto_isolir']]);

                            Notification::make()
                                ->title('Auto Isolir Updated')
                                ->body("Auto isolir for {$record->username} has been ".($data['auto_isolir'] ? 'enabled' : 'disabled').'.')
                                ->success()
                                ->send();
                        }),
                    Action::make('isolir')
                        ->color('danger')
                        ->icon(Heroicon::OutlinedSignalSlash)
                        ->visible(fn (Customer $record) => empty($record->isolir_at))
                        ->requiresConfirmation()
                        ->action(function (Customer $record) {

                            $response = (new MikrotikAPINative)->isolirClient($record->username, true);

                            if (isset($response['error'])) {
                                Notification::make()
                                    ->title('Isolir Failed : '.$record->username)
                                    ->body(json_encode($response))
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $record->update([
                                'isolir_at' => now(),
                                'comment' => $response['comment'],
                            ]);

                            Notification::make()
                                ->title('Customer Isolated')
                                ->body('Customer '.$record->username.' has been isolated successfully.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}
