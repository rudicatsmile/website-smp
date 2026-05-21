<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMedias;

use App\Filament\Resources\LearningMedias\Pages\CreateLearningMedia;
use App\Filament\Resources\LearningMedias\Pages\EditLearningMedia;
use App\Filament\Resources\LearningMedias\Pages\ListLearningMedias;
use App\Models\LearningMedia;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LearningMediaResource extends Resource
{
    protected static ?string $model = LearningMedia::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tv';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationLabel = 'Media Pembelajaran';

    protected static ?string $modelLabel = 'Media Pembelajaran';

    protected static ?string $pluralModelLabel = 'Media Pembelajaran';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')
                ->label('Nama Media Pembelajaran')
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
                    ->label('Media Pembelajaran')
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
            'index'  => ListLearningMedias::route('/'),
            'create' => CreateLearningMedia::route('/create'),
            'edit'   => EditLearningMedia::route('/{record}/edit'),
        ];
    }
}
