<?php

namespace App\Filament\Resources\VirtualAccounts;

use App\Filament\Resources\VirtualAccounts\Pages\ListVirtualAccounts;
use App\Filament\Resources\VirtualAccounts\Schemas\VirtualAccountForm;
use App\Filament\Resources\VirtualAccounts\Tables\VirtualAccountsTable;
use App\Models\VirtualAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class VirtualAccountResource extends Resource
{
    protected static ?string $model = VirtualAccount::class;

    protected static string|UnitEnum|null $navigationGroup = 'Transactions';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $recordTitleAttribute = 'va_number';

    public static function form(Schema $schema): Schema
    {
        return VirtualAccountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VirtualAccountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVirtualAccounts::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
