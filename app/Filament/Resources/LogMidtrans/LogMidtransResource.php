<?php

namespace App\Filament\Resources\LogMidtrans;

use App\Filament\Resources\LogMidtrans\Pages\CreateLogMidtrans;
use App\Filament\Resources\LogMidtrans\Pages\EditLogMidtrans;
use App\Filament\Resources\LogMidtrans\Pages\ListLogMidtrans;
use App\Filament\Resources\LogMidtrans\Pages\ViewLogMidtrans;
use App\Filament\Resources\LogMidtrans\Schemas\LogMidtransForm;
use App\Filament\Resources\LogMidtrans\Schemas\LogMidtransInfolist;
use App\Filament\Resources\LogMidtrans\Tables\LogMidtransTable;
use App\Models\LogMidtrans;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class LogMidtransResource extends Resource
{
    protected static ?string $model = LogMidtrans::class;

    protected static string | UnitEnum | null $navigationGroup = 'Logs';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return LogMidtransForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LogMidtransInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogMidtransTable::configure($table);
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
            'index' => ListLogMidtrans::route('/'),
            'create' => CreateLogMidtrans::route('/create'),
            'view' => ViewLogMidtrans::route('/{record}'),
            'edit' => EditLogMidtrans::route('/{record}/edit'),
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
