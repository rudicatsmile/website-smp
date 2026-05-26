<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuranSurahs;

use App\Filament\Resources\QuranSurahs\Pages\CreateQuranSurah;
use App\Filament\Resources\QuranSurahs\Pages\EditQuranSurah;
use App\Filament\Resources\QuranSurahs\Pages\ListQuranSurahs;
use App\Models\QuranSurah;
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

class QuranSurahResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = QuranSurah::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 16;

    protected static ?string $navigationLabel = 'Surat Al-Quran';

    protected static ?string $modelLabel = 'Surat Al-Quran';

    protected static ?string $pluralModelLabel = 'Surat Al-Quran';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')
                ->label('Nama Surat')
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
                    ->label('Nama Surat')
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
            'index'  => ListQuranSurahs::route('/'),
            'create' => CreateQuranSurah::route('/create'),
            'edit'   => EditQuranSurah::route('/{record}/edit'),
        ];
    }
}
