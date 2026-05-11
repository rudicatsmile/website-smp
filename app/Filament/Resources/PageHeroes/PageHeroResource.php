<?php

namespace App\Filament\Resources\PageHeroes;

use App\Filament\Resources\PageHeroes\Pages\CreatePageHero;
use App\Filament\Resources\PageHeroes\Pages\EditPageHero;
use App\Filament\Resources\PageHeroes\Pages\ListPageHeroes;
use App\Filament\Resources\PageHeroes\Schemas\PageHeroForm;
use App\Filament\Resources\PageHeroes\Tables\PageHeroesTable;
use App\Models\PageHero;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PageHeroResource extends Resource
{
    protected static ?string $model = PageHero::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan Umum';

    protected static ?int $navigationSort = 70;

    protected static ?string $navigationLabel = 'Page Hero';

    protected static ?string $modelLabel = 'Page Hero';

    protected static ?string $pluralModelLabel = 'Page Heroes';

    public static function form(Schema $schema): Schema
    {
        return PageHeroForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageHeroesTable::configure($table);
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
            'index' => ListPageHeroes::route('/'),
            'create' => CreatePageHero::route('/create'),
            'edit' => EditPageHero::route('/{record}/edit'),
        ];
    }
}
