<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningObjectives;

use App\Filament\Resources\LearningObjectives\Pages\CreateLearningObjective;
use App\Filament\Resources\LearningObjectives\Pages\EditLearningObjective;
use App\Filament\Resources\LearningObjectives\Pages\ListLearningObjectives;
use App\Models\LearningObjective;
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

class LearningObjectiveResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = LearningObjective::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-light-bulb';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Tujuan Pembelajaran';

    protected static ?string $modelLabel = 'Tujuan Pembelajaran';

    protected static ?string $pluralModelLabel = 'Tujuan Pembelajaran';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')
                ->label('Nama Tujuan Pembelajaran')
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
                    ->label('Tujuan Pembelajaran')
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
            'index'  => ListLearningObjectives::route('/'),
            'create' => CreateLearningObjective::route('/create'),
            'edit'   => EditLearningObjective::route('/{record}/edit'),
        ];
    }
}
