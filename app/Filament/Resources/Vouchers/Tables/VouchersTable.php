<?php

namespace App\Filament\Resources\Vouchers\Tables;

use App\Models\TemplateMessage;
use App\Services\WahaService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('expired_at')
                    ->dateTime()
                    ->searchable(),
                TextColumn::make('whatsapp_number'),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('duration')
                    ->formatStateUsing(fn($record, $state) => $state . ' ' . match ($record->duration_type) {
                        'd' => 'Days',
                        'h' => 'Hours',
                        default => 'None'
                    }),
                IconColumn::make('status')
                    ->boolean()
                    ->searchable(),
                TextColumn::make('price')
                    ->money('IDR'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions(ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                Action::make('send_wa')
                    ->label('Send to WhatsApp')
                    ->icon(Heroicon::PaperAirplane)
                    ->action(function (Model $record, WahaService $service) {
                        $template = TemplateMessage::getTemplate($record);
                        if (!$template) {
                            return;
                        }

                        $service->sendTemplatedMessage(
                            $template,
                            $record->whatsapp_number,
                            ['voucher' => $record->code]
                        );
                    })
                    ->visible(fn(Model $record) => !empty($record->whatsapp_number))

            ]))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
