<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\Packages;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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
                            ->columnSpanFull()
                    ])
                    ->columns()
                    ->columnSpanFull(),
                Section::make('Security')
                    ->description('Account security information for password')
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
                            })
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
                    ->description('Router information for PPP configuration')
                    ->collapsed()
                    ->schema([
                        TextInput::make('username')
                            ->label('Username PPP'),
                        TextInput::make('password_pptp'),
                        TextInput::make('local_address'),
                        TextInput::make('remote_address'),
                    ])
                    ->afterHeader([
                        Action::make('sync')
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
