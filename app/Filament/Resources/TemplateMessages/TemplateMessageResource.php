<?php

namespace App\Filament\Resources\TemplateMessages;

use App\Filament\Resources\TemplateMessages\Pages\CreateTemplateMessage;
use App\Filament\Resources\TemplateMessages\Pages\EditTemplateMessage;
use App\Filament\Resources\TemplateMessages\Pages\ListTemplateMessages;
use App\Filament\Resources\TemplateMessages\Pages\ViewTemplateMessage;
use App\Filament\Resources\TemplateMessages\Schemas\TemplateMessageForm;
use App\Filament\Resources\TemplateMessages\Schemas\TemplateMessageInfolist;
use App\Filament\Resources\TemplateMessages\Tables\TemplateMessagesTable;
use App\Models\TemplateMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TemplateMessageResource extends Resource
{
    protected static ?string $model = TemplateMessage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    public static function form(Schema $schema): Schema
    {
        return TemplateMessageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TemplateMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TemplateMessagesTable::configure($table);
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
            'index' => ListTemplateMessages::route('/'),
            'create' => CreateTemplateMessage::route('/create'),
            'view' => ViewTemplateMessage::route('/{record}'),
            'edit' => EditTemplateMessage::route('/{record}/edit'),
        ];
    }
}
