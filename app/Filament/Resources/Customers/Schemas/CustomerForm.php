<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Discount;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Filament\Support\Icons\Heroicon;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account')
                    ->description('Account information')
                    ->aside()
                    ->schema([
                        TextInput::make('full_name'),
                        Select::make('customer_category')
                            ->options(Discount::getCategoryStatus()),
                        TextInput::make('email')
                            ->email(),
                        TextInput::make('phone')
                            ->tel(),
                        Textarea::make('address')
                            ->columnSpanFull(),
                        Toggle::make('auto_isolir')->default(true),
                    ])
                    ->columns()
                    ->columnSpanFull(),
                Section::make('Security')
                    ->description('Account security information for password')
                    ->visibleOn(Operation::Edit)
                    ->footerActions([
                        Action::make('update_password')
                            ->icon(Heroicon::OutlinedKey)
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function ($get, Customer $record) {
                                if ($get('new_password') != $get('confirm_password')) {
                                    Notification::make()
                                        ->title('Failed')
                                        ->body('Password mismatch')
                                        ->danger()->send();

                                    return;
                                }

                                $record->setPasswordAttribute($get('new_password'));
                                $record->save();

                                Notification::make()
                                    ->title('Success')
                                    ->body('Password updated')
                                    ->success()->send();
                            }),
                    ])
                    ->aside()
                    ->schema([
                        TextInput::make('new_password')->password()
                            ->revealable(),
                        TextInput::make('confirm_password')->password()
                            ->revealable(),
                    ])
                    ->columnSpanFull(),
                Section::make('Router')
                    ->aside()
                    ->description('Router information for PPP configuration')
                    ->collapsed()
                    ->schema([
                        TextInput::make('username')
                            ->label('Username PPP'),
                        TextInput::make('password_pptp'),
                        TextInput::make('local_address'),
                        TextInput::make('remote_address'),
                    ])
                    ->footerActions([
                        Action::make('sync')
                            ->icon(Heroicon::OutlinedArrowPath)
                            ->color('info')
                            ->requiresConfirmation()
                            ->visible(fn (?Customer $record) => $record !== null)
                            ->action(function ($get, Customer $record) {
                                $api = new MikrotikAPINative;

                                $params = [
                                    'username' => $get('username'),
                                    'password' => $get('password_pptp'),
                                    'local_address' => $get('local_address'),
                                    'remote_address' => $get('remote_address'),
                                ];

                                if ($record->pppProfile) {
                                    $params['profile'] = $record->pppProfile->profile_name;
                                }

                                $response = $api->updatePppSecret($record->username, $params);

                                if (isset($response['status']) && $response['status']) {
                                    Notification::make()
                                        ->title('Sync Failed')
                                        ->body($response['message'] ?? 'MikroTik connection error')
                                        ->danger()
                                        ->send();

                                    return;
                                }

                                $record->update([
                                    'username' => $get('username'),
                                    'password_pptp' => $get('password_pptp'),
                                    'local_address' => $get('local_address'),
                                    'remote_address' => $get('remote_address'),
                                ]);

                                Notification::make()
                                    ->title('Success')
                                    ->body('PPP Secret synced and record updated')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
