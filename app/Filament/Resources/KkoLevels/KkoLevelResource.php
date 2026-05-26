<?php

declare(strict_types=1);

namespace App\Filament\Resources\KkoLevels;

use App\Filament\Resources\KkoLevels\Pages\CreateKkoLevel;
use App\Filament\Resources\KkoLevels\Pages\EditKkoLevel;
use App\Filament\Resources\KkoLevels\Pages\ListKkoLevels;
use App\Models\KkoLevel;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use App\Filament\Concerns\HidesFromEkskulRole;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KkoLevelResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = KkoLevel::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-signal';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 14;

    protected static ?string $navigationLabel = 'Level KKO';

    protected static ?string $modelLabel = 'Level KKO';

    protected static ?string $pluralModelLabel = 'Level KKO';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')
                ->label('Nama Level KKO')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0)
                ->minValue(0),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                TextColumn::make('name')
                    ->label('Level KKO')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListKkoLevels::route('/'),
            'create' => CreateKkoLevel::route('/create'),
            'edit'   => EditKkoLevel::route('/{record}/edit'),
        ];
    }
}
