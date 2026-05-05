<?php

namespace App\Filament\Resources\CustomerInstallations;

use App\Filament\Resources\CustomerInstallations\Pages\CreateCustomerInstallation;
use App\Filament\Resources\CustomerInstallations\Pages\EditCustomerInstallation;
use App\Filament\Resources\CustomerInstallations\Pages\ListCustomerInstallations;
use App\Filament\Resources\CustomerInstallations\Pages\ViewCustomerInstallation;
use App\Filament\Resources\CustomerInstallations\Schemas\CustomerInstallationForm;
use App\Filament\Resources\CustomerInstallations\Schemas\CustomerInstallationInfolist;
use App\Filament\Resources\CustomerInstallations\Tables\CustomerInstallationsTable;
use App\Models\CustomerInstallationOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CustomerInstallationResource extends Resource
{
    protected static ?string $model = CustomerInstallationOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static string|UnitEnum|null $navigationGroup = 'Master';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Installation';

    public static function form(Schema $schema): Schema
    {
        return CustomerInstallationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInstallationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerInstallationsTable::configure($table);
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
            'index' => ListCustomerInstallations::route('/'),
            'create' => CreateCustomerInstallation::route('/create'),
            'view' => ViewCustomerInstallation::route('/{record}'),
            'edit' => EditCustomerInstallation::route('/{record}/edit'),
        ];
    }
}
