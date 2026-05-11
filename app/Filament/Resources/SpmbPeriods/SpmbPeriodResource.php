<?php

namespace App\Filament\Resources\SpmbPeriods;

use App\Filament\Resources\SpmbPeriods\Pages\CreateSpmbPeriod;
use App\Filament\Resources\SpmbPeriods\Pages\EditSpmbPeriod;
use App\Filament\Resources\SpmbPeriods\Pages\ListSpmbPeriods;
use App\Filament\Resources\SpmbPeriods\Schemas\SpmbPeriodForm;
use App\Filament\Resources\SpmbPeriods\Tables\SpmbPeriodsTable;
use App\Models\SpmbPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpmbPeriodResource extends Resource
{
    protected static ?string $model = SpmbPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'PPDB';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Periode PPDB';

    protected static ?string $modelLabel = 'Periode PPDB';

    protected static ?string $pluralModelLabel = 'Periode PPDB';

    public static function form(Schema $schema): Schema
    {
        return SpmbPeriodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpmbPeriodsTable::configure($table);
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
            'index' => ListSpmbPeriods::route('/'),
            'create' => CreateSpmbPeriod::route('/create'),
            'edit' => EditSpmbPeriod::route('/{record}/edit'),
        ];
    }
}
