<?php

namespace App\Filament\Admin\Resources\CoverageAreas;

use App\Filament\Admin\Resources\CoverageAreas\Pages\CreateCoverageArea;
use App\Filament\Admin\Resources\CoverageAreas\Pages\EditCoverageArea;
use App\Filament\Admin\Resources\CoverageAreas\Pages\ListCoverageAreas;
use App\Filament\Admin\Resources\CoverageAreas\Schemas\CoverageAreaForm;
use App\Filament\Admin\Resources\CoverageAreas\Tables\CoverageAreasTable;
use App\Models\CoverageArea;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CoverageAreaResource extends Resource
{
    protected static ?string $model = CoverageArea::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $navigationLabel = 'Coverage Area';

    protected static ?string $modelLabel = 'Area Jangkauan';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Layanan';

    public static function form(Schema $schema): Schema
    {
        return CoverageAreaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoverageAreasTable::configure($table);
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
            'index' => ListCoverageAreas::route('/'),
            'create' => CreateCoverageArea::route('/create'),
            'edit' => EditCoverageArea::route('/{record}/edit'),
        ];
    }
}
