<?php

namespace App\Filament\Resources\CustomerConnections;

use App\Filament\Resources\CustomerConnections\Pages\CreateCustomerConnection;
use App\Filament\Resources\CustomerConnections\Pages\EditCustomerConnection;
use App\Filament\Resources\CustomerConnections\Pages\ListCustomerConnections;
use App\Filament\Resources\CustomerConnections\Schemas\CustomerConnectionForm;
use App\Filament\Resources\CustomerConnections\Tables\CustomerConnectionsTable;
use App\Models\CustomerConnection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CustomerConnectionResource extends Resource
{
    protected static ?string $model = CustomerConnection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;

    public static function form(Schema $schema): Schema
    {
        return CustomerConnectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerConnectionsTable::configure($table);
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
            'index' => ListCustomerConnections::route('/'),
            'create' => CreateCustomerConnection::route('/create'),
            'edit' => EditCustomerConnection::route('/{record}/edit'),
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
