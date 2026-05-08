<?php

namespace App\Filament\Resources\SpmbRegistrations;

use App\Filament\Resources\SpmbRegistrations\Pages\CreateSpmbRegistration;
use App\Filament\Resources\SpmbRegistrations\Pages\EditSpmbRegistration;
use App\Filament\Resources\SpmbRegistrations\Pages\ListSpmbRegistrations;
use App\Filament\Resources\SpmbRegistrations\Schemas\SpmbRegistrationForm;
use App\Filament\Resources\SpmbRegistrations\Tables\SpmbRegistrationsTable;
use App\Models\SpmbRegistration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpmbRegistrationResource extends Resource
{
    protected static ?string $model = SpmbRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?int $navigationSort = 31;

    protected static ?string $navigationLabel = 'Pendaftar PPDB';

    protected static ?string $modelLabel = 'Pendaftar';

    protected static ?string $pluralModelLabel = 'Pendaftar';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return SpmbRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpmbRegistrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\SpmbRegistrations\RelationManagers\DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpmbRegistrations::route('/'),
            'create' => CreateSpmbRegistration::route('/create'),
            'edit' => EditSpmbRegistration::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
