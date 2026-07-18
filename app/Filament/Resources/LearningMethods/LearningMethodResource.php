<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMethods;

use App\Filament\Resources\LearningMethods\Pages\CreateLearningMethod;
use App\Filament\Resources\LearningMethods\Pages\EditLearningMethod;
use App\Filament\Resources\LearningMethods\Pages\ListLearningMethods;
use App\Models\LearningMethod;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use App\Filament\Concerns\HidesFromEkskulRole;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LearningMethodResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = LearningMethod::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationLabel = 'Metode Pembelajaran';

    protected static ?string $modelLabel = 'Metode Pembelajaran';

    protected static ?string $pluralModelLabel = 'Metode Pembelajaran';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Select::make('material_category_id')
                ->label('Mata Pelajaran')
                ->relationship(
                    name: 'subject', 
                    titleAttribute: 'name', 
                    modifyQueryUsing: function ($query) {
                        $user = auth()->user();
                        if ($user && $user->hasRole('teacher') && $user->staffMember) {
                            $subjectIds = $user->staffMember->teachingSubjects()->pluck('material_categories.id')->toArray();
                            $query->whereIn('id', $subjectIds);
                        }
                    }
                )
                ->required()
                ->searchable()
                ->preload()
                ->columnSpanFull(),
            TextInput::make('name')
                ->label('Nama Metode Pembelajaran')
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
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Metode Pembelajaran')
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
            ->filters([
                SelectFilter::make('material_category_id')
                    ->label('Mata Pelajaran')
                    ->relationship(
                        name: 'subject', 
                        titleAttribute: 'name', 
                        modifyQueryUsing: function ($query) {
                            $user = auth()->user();
                            if ($user && $user->hasRole('teacher') && $user->staffMember) {
                                $subjectIds = $user->staffMember->teachingSubjects()->pluck('material_categories.id')->toArray();
                                $query->whereIn('id', $subjectIds);
                            }
                        }
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        
        if ($user && $user->hasRole('teacher') && $user->staffMember) {
            $subjectIds = $user->staffMember->teachingSubjects()->pluck('material_categories.id')->toArray();
            $query->whereIn('material_category_id', $subjectIds);
        }
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLearningMethods::route('/'),
            'create' => CreateLearningMethod::route('/create'),
            'edit'   => EditLearningMethod::route('/{record}/edit'),
        ];
    }
}
