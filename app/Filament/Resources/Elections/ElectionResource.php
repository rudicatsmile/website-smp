<?php

namespace App\Filament\Resources\Elections;

use App\Filament\Resources\Elections\Pages\CreateElection;
use App\Filament\Resources\Elections\Pages\EditElection;
use App\Filament\Resources\Elections\Pages\ListElections;
use App\Filament\Resources\Elections\Schemas\ElectionForm;
use App\Filament\Resources\Elections\Tables\ElectionsTable;
use App\Models\Election;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ElectionResource extends Resource
{
    protected static ?string $model = Election::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Election';
    protected static ?string $navigationLabel = 'Data Elections';

    public static function form(Schema $schema): Schema
    {
        return ElectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ElectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ElectionResource\RelationManagers\CandidatesRelationManager::class,
            ElectionResource\RelationManagers\VotersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListElections::route('/'),
            'create' => CreateElection::route('/create'),
            'edit' => EditElection::route('/{record}/edit'),
        ];
    }
}
