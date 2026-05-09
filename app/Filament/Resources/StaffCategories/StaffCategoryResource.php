<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffCategories;

use App\Filament\Resources\StaffCategories\Pages\CreateStaffCategory;
use App\Filament\Resources\StaffCategories\Pages\EditStaffCategory;
use App\Filament\Resources\StaffCategories\Pages\ListStaffCategories;
use App\Filament\Resources\StaffCategories\Schemas\StaffCategoryForm;
use App\Filament\Resources\StaffCategories\Tables\StaffCategoriesTable;
use App\Models\StaffCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StaffCategoryResource extends Resource
{
    protected static ?string $model = StaffCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return StaffCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaffCategories::route('/'),
            'create' => CreateStaffCategory::route('/create'),
            'edit' => EditStaffCategory::route('/{record}/edit'),
        ];
    }
}
