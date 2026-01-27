<?php

namespace App\Filament\Resources\LogMikrotiks;

use App\Filament\Resources\LogMikrotiks\Pages\CreateLogMikrotik;
use App\Filament\Resources\LogMikrotiks\Pages\EditLogMikrotik;
use App\Filament\Resources\LogMikrotiks\Pages\ListLogMikrotiks;
use App\Filament\Resources\LogMikrotiks\Pages\ViewLogMikrotik;
use App\Filament\Resources\LogMikrotiks\Schemas\LogMikrotikForm;
use App\Filament\Resources\LogMikrotiks\Schemas\LogMikrotikInfolist;
use App\Filament\Resources\LogMikrotiks\Tables\LogMikrotiksTable;
use App\Models\LogMikrotik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LogMikrotikResource extends Resource
{
    protected static ?string $model = LogMikrotik::class;

    protected static string | UnitEnum | null $navigationGroup = 'Logs';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return LogMikrotikForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LogMikrotikInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogMikrotiksTable::configure($table);
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
            'index' => ListLogMikrotiks::route('/'),
            'create' => CreateLogMikrotik::route('/create'),
            'view' => ViewLogMikrotik::route('/{record}'),
            'edit' => EditLogMikrotik::route('/{record}/edit'),
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

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
