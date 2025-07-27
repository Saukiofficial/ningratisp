<?php

namespace App\Filament\Resources\PppProfiles;

use App\Filament\Resources\PppProfiles\Pages\CreatePppProfile;
use App\Filament\Resources\PppProfiles\Pages\EditPppProfile;
use App\Filament\Resources\PppProfiles\Pages\ListPppProfiles;
use App\Filament\Resources\PppProfiles\Pages\ViewPppProfile;
use App\Filament\Resources\PppProfiles\Schemas\PppProfileForm;
use App\Filament\Resources\PppProfiles\Schemas\PppProfileInfolist;
use App\Filament\Resources\PppProfiles\Tables\PppProfilesTable;
use App\Models\PppProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PppProfileResource extends Resource
{
    protected static ?string $model = PppProfile::class;

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-server';

    public static function form(Schema $schema): Schema
    {
        return PppProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PppProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PppProfilesTable::configure($table);
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
            'index' => ListPppProfiles::route('/'),
            'create' => CreatePppProfile::route('/create'),
            'view' => ViewPppProfile::route('/{record}'),
            'edit' => EditPppProfile::route('/{record}/edit'),
        ];
    }
}
