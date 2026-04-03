<?php

namespace App\Filament\Resources\CustomerInstallations\Tables;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\CustomerInstallationOrder;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerInstallationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('area.name')
                    ->sortable(),
                TextColumn::make('package.package_label')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        CustomerInstallationOrder::STATUS_PENDING => 'gray',
                        CustomerInstallationOrder::STATUS_ON_PROGRESS => 'warning',
                        CustomerInstallationOrder::STATUS_DONE => 'success',
                        CustomerInstallationOrder::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => CustomerInstallationOrder::getStatusLabel()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('installation_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('finished_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reffer_by')
                    ->label('Referred By')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('markInProgress')
                        ->label('Start Progress')
                        ->icon(Heroicon::OutlinedPlay)
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn (CustomerInstallationOrder $record) => $record->status === CustomerInstallationOrder::STATUS_PENDING)
                        ->action(function (CustomerInstallationOrder $record) {
                            $record->update(['status' => CustomerInstallationOrder::STATUS_ON_PROGRESS]);
                            Notification::make()->title('Installation started')->success()->send();
                        }),

                    Action::make('backPendingInstallation')
                        ->label('Back Pending')
                        ->icon(Heroicon::OutlinedPlayPause)
                        ->color(Color::Gray[100])
                        ->visible(
                            fn (CustomerInstallationOrder $record) => in_array($record->status, [CustomerInstallationOrder::STATUS_CANCELLED, CustomerInstallationOrder::STATUS_ON_PROGRESS])
                        )
                        ->requiresConfirmation()
                        ->action(function (CustomerInstallationOrder $record, array $data) {
                            $record->update([
                                'status' => CustomerInstallationOrder::STATUS_PENDING,
                            ]);
                            Notification::make()->title('Installation Pendng')->info()->send();
                        }),

                    Action::make('cancelInstallation')
                        ->label('Cancel')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (CustomerInstallationOrder $record) => in_array($record->status, [CustomerInstallationOrder::STATUS_PENDING, CustomerInstallationOrder::STATUS_ON_PROGRESS]))
                        ->schema([
                            Textarea::make('cancelled_reason')
                                ->required(),
                        ])
                        ->action(function (CustomerInstallationOrder $record, array $data) {
                            $record->update([
                                'status' => CustomerInstallationOrder::STATUS_CANCELLED,
                                'cancelled_reason' => $data['cancelled_reason'],
                            ]);
                            Notification::make()->title('Installation cancelled')->danger()->send();
                        }),

                    Action::make('finishInstallation')
                        ->label('Finish & Create Customer')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->visible(fn (CustomerInstallationOrder $record) => $record->status === CustomerInstallationOrder::STATUS_ON_PROGRESS)
                        ->schema([
                            DateTimePicker::make('finished_date')
                                ->default(now())
                                ->required(),
                            TextInput::make('username')
                                ->label('PPPOE Username')
                                ->required()
                                ->helperText('Will be combined with area prefix automatically if you follow convention'),
                            TextInput::make('password_pptp')
                                ->label('PPPOE Password')
                                ->default('12345')
                                ->required(),
                        ])
                        ->action(function (CustomerInstallationOrder $record, array $data) {
                            // Logic to create customer
                            // This should ideally use the same logic as CreateCustomer.php
                            // For now, let's redirect to CreateCustomer with parameters if possible,
                            // or just implement the creation here since it's "finishing this feature".

                            // Let's redirect to CreateCustomer page with prefilled data
                            return redirect()->to(CustomerResource::getUrl('create', [
                                'installation_order_id' => $record->id,
                                'full_name' => $record->name,
                                'phone' => $record->phone,
                                'area' => $record->area?->customer_prefix,
                                'package_id' => $record->package_id,
                                'package_selected' => $record->package->package_label,
                                'ppp_user' => $data['username'],
                                'password_pptp' => $data['password_pptp'],
                            ]));
                        }),

                    Action::make('openMap')
                        ->label('Maps')
                        ->icon(Heroicon::OutlinedMapPin)
                        ->color('info')
                        ->url(fn (CustomerInstallationOrder $record) => $record->maps_link)
                        ->openUrlInNewTab(),

                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
