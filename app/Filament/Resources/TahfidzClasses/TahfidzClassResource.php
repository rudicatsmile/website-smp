<?php

declare(strict_types=1);

namespace App\Filament\Resources\TahfidzClasses;

use App\Filament\Resources\TahfidzClasses\Pages\CreateTahfidzClass;
use App\Filament\Resources\TahfidzClasses\Pages\EditTahfidzClass;
use App\Filament\Resources\TahfidzClasses\Pages\ListTahfidzClasses;
use App\Models\TahfidzClass;
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

class TahfidzClassResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = TahfidzClass::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 15;

    protected static ?string $navigationLabel = 'Kelas Tahfidz';

    protected static ?string $modelLabel = 'Kelas Tahfidz';

    protected static ?string $pluralModelLabel = 'Kelas Tahfidz';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')
                ->label('Nama Kelas Tahfidz')
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
                    ->label('Kelas Tahfidz')
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
            'index'  => ListTahfidzClasses::route('/'),
            'create' => CreateTahfidzClass::route('/create'),
            'edit'   => EditTahfidzClass::route('/{record}/edit'),
        ];
    }
}
