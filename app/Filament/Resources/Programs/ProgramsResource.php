<?php

namespace App\Filament\Resources\Programs;

use App\Filament\Resources\Programs\Pages\CreatePrograms;
use App\Filament\Resources\Programs\Pages\EditPrograms;
use App\Filament\Resources\Programs\Pages\ListPrograms;
use App\Filament\Resources\Programs\Schemas\ProgramsForm;
use App\Filament\Resources\Programs\Tables\ProgramsTable;
use App\Models\Program;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgramsResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Sparkles;

    protected static ?int $navigationSort = 15;

    protected static ?string $navigationLabel = 'Program Unggulan';

    protected static ?string $modelLabel = 'Program Unggulan';

    protected static ?string $pluralModelLabel = 'Program Unggulan';

    public static function form(Schema $schema): Schema
    {
        return ProgramsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramsTable::configure($table);
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
            'index' => ListPrograms::route('/'),
            'create' => CreatePrograms::route('/create'),
            'edit' => EditPrograms::route('/{record}/edit'),
        ];
    }
}
