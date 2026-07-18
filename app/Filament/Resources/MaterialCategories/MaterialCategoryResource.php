<?php

declare(strict_types=1);

namespace App\Filament\Resources\MaterialCategories;

use App\Filament\Resources\MaterialCategories\Pages\CreateMaterialCategory;
use App\Filament\Resources\MaterialCategories\Pages\EditMaterialCategory;
use App\Filament\Resources\MaterialCategories\Pages\ListMaterialCategories;
use App\Filament\Resources\MaterialCategories\Schemas\MaterialCategoryForm;
use App\Filament\Resources\MaterialCategories\Tables\MaterialCategoriesTable;
use App\Models\MaterialCategory;
use BackedEnum;
use App\Filament\Concerns\HidesFromEkskulRole;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MaterialCategoryResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = MaterialCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Mata Pelajaran';

    protected static ?string $modelLabel = 'Mata Pelajaran';

    protected static ?string $pluralModelLabel = 'Mata Pelajaran';

    public static function form(Schema $schema): Schema
    {
        return MaterialCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaterialCategories::route('/'),
            'create' => CreateMaterialCategory::route('/create'),
            'edit' => EditMaterialCategory::route('/{record}/edit'),
        ];
    }
}
