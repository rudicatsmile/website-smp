<?php

declare(strict_types=1);

namespace App\Filament\Resources\AssessmentTypes;

use App\Filament\Resources\AssessmentTypes\Pages\CreateAssessmentType;
use App\Filament\Resources\AssessmentTypes\Pages\EditAssessmentType;
use App\Filament\Resources\AssessmentTypes\Pages\ListAssessmentTypes;
use App\Models\AssessmentType;
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

class AssessmentTypeResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = AssessmentType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationLabel = 'Jenis Asesmen';

    protected static ?string $modelLabel = 'Jenis Asesmen';

    protected static ?string $pluralModelLabel = 'Jenis Asesmen';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')
                ->label('Nama Jenis Asesmen')
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
                    ->label('Jenis Asesmen')
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
            'index'  => ListAssessmentTypes::route('/'),
            'create' => CreateAssessmentType::route('/create'),
            'edit'   => EditAssessmentType::route('/{record}/edit'),
        ];
    }
}
