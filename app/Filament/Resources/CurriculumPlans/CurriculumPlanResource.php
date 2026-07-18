<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans;

use App\Filament\Resources\CurriculumPlans\Pages\CreateCurriculumPlan;
use App\Filament\Resources\CurriculumPlans\Pages\EditCurriculumPlan;
use App\Filament\Resources\CurriculumPlans\Pages\ListCurriculumPlans;
use App\Filament\Resources\CurriculumPlans\RelationManagers\TopicsRelationManager;
use App\Models\CurriculumPlan;
use App\Models\LearningMedia;
use App\Models\LearningMethod;
use App\Models\LearningModel;
use App\Models\LearningObjective;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use App\Filament\Concerns\HidesFromEkskulRole;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CurriculumPlanResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = CurriculumPlan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Kurikulum';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Rencana Pembelajaran';

    protected static ?string $modelLabel = 'Rencana Pembelajaran';

    protected static ?string $pluralModelLabel = 'Rencana Pembelajaran';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Rencana Pembelajaran')->schema([
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('material_category_id')->label('Mata Pelajaran')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required()
                    ->live(),
                Select::make('staff_member_id')->label('Guru Pengampu')
                    ->options(function (Get $get) {
                        $subjectId = $get('material_category_id');
                        $query = StaffMember::active()->orderBy('name');
                        if ($subjectId) {
                            $query->whereHas('teachingSubjects', fn ($q) => $q->where('material_categories.id', $subjectId));
                        }
                        return $query->pluck('name', 'id');
                    })
                    ->searchable()->preload(),
                TextInput::make('academic_year')->label('Tahun Ajaran')->required()->maxLength(20)
                    ->placeholder('2025/2026'),
                Select::make('semester')->label('Semester')->required()
                    ->options(['ganjil' => 'Ganjil', 'genap' => 'Genap']),
                Toggle::make('is_active')->label('Aktif')->default(true),
                TextInput::make('title')->label('Topik')->required()->maxLength(200)->columnSpanFull(),
                TextInput::make('time_allocation')->label('Alokasi Waktu')->maxLength(50)
                    ->placeholder('Contoh: 2 x 40 menit'),
                Select::make('learning_objective_ids')
                    ->label('Tujuan Pembelajaran')
                    ->multiple()
                    ->options(function (Get $get) {
                        $subjectId = $get('material_category_id');
                        if (! $subjectId) {
                            return [];
                        }
                        return LearningObjective::active()->where('material_category_id', $subjectId)->ordered()->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Select::make('learning_model_ids')
                    ->label('Model Pembelajaran')
                    ->multiple()
                    ->options(fn () => LearningModel::active()->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Select::make('default_methods')
                    ->label('Metode Pembelajaran')
                    ->multiple()
                    ->options(fn () => LearningMethod::active()->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Select::make('default_media')
                    ->label('Media Pembelajaran')
                    ->multiple()
                    ->options(fn () => LearningMedia::active()->ordered()->pluck('name', 'id')->put('lainnya', '— Lainnya'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->columnSpanFull(),
                TextInput::make('default_media_other')
                    ->label('Media Lainnya (isian bebas)')
                    ->maxLength(255)
                    ->placeholder('Tulis media pembelajaran lainnya...')
                    ->columnSpanFull()
                    ->hidden(fn (Get $get): bool => ! in_array('lainnya', (array) ($get('default_media') ?? []))),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->limit(40),
                TextColumn::make('schoolClass.name')->label('Kelas')->badge(),
                TextColumn::make('subject.name')->label('Mapel')->badge()->color('info'),
                TextColumn::make('teacher.name')->label('Guru')->placeholder('—')->toggleable(),
                TextColumn::make('academic_year')->label('Tahun')->toggleable(),
                TextColumn::make('semester')->label('Semester')->badge()->toggleable()
                    ->formatStateUsing(fn ($state) => strtolower((string)$state) === 'ganjil' ? 'Ganjil' : (strtolower((string)$state) === 'genap' ? 'Genap' : ucfirst((string)$state))),
                TextColumn::make('topics_count')->label('Topik')->counts('topics')->badge(),
                TextColumn::make('sessions_count')->label('Sesi')->counts('sessions')->badge()->color('warning'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::ordered()->pluck('name', 'id')),
                SelectFilter::make('material_category_id')->label('Mapel')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id')),
                SelectFilter::make('academic_year')->label('Tahun Ajaran')
                    ->options(fn () => CurriculumPlan::distinct()->pluck('academic_year', 'academic_year')->filter()),
                SelectFilter::make('semester')->label('Semester')
                    ->options(['ganjil' => 'Ganjil', 'genap' => 'Genap']),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [TopicsRelationManager::class];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            $staffId = $user->staffMember?->id;
            $query->where('staff_member_id', $staffId);
        }
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCurriculumPlans::route('/'),
            'create' => CreateCurriculumPlan::route('/create'),
            'edit' => EditCurriculumPlan::route('/{record}/edit'),
        ];
    }
}
