<?php

namespace App\Filament\Resources\Academics;

use App\Filament\Resources\Academics\Pages\CreateAcademic;
use App\Filament\Resources\Academics\Pages\EditAcademic;
use App\Filament\Resources\Academics\Pages\ListAcademics;
use App\Filament\Resources\Academics\Schemas\AcademicForm;
use App\Filament\Resources\Academics\Tables\AcademicsTable;
use App\Models\Academic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AcademicResource extends Resource
{
    protected static ?string $model = Academic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Akademik';

    protected static ?string $modelLabel = 'Program Akademik';

    protected static ?string $pluralModelLabel = 'Program Akademik';

    public static function form(Schema $schema): Schema
    {
        return AcademicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicsTable::configure($table);
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
            'index' => ListAcademics::route('/'),
            'create' => CreateAcademic::route('/create'),
            'edit' => EditAcademic::route('/{record}/edit'),
        ];
    }
}
