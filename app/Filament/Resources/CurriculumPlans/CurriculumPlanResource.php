<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans;

use App\Filament\Resources\CurriculumPlans\Pages\CreateCurriculumPlan;
use App\Filament\Resources\CurriculumPlans\Pages\EditCurriculumPlan;
use App\Filament\Resources\CurriculumPlans\Pages\ListCurriculumPlans;
use App\Filament\Resources\CurriculumPlans\RelationManagers\TopicsRelationManager;
use App\Models\CurriculumPlan;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
    protected static ?string $model = CurriculumPlan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Materi Pelajaran';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Kurikulum';

    protected static ?string $modelLabel = 'Rencana Kurikulum';

    protected static ?string $pluralModelLabel = 'Kurikulum';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kurikulum')->columns(3)->schema([
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('material_category_id')->label('Mata Pelajaran')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('staff_member_id')->label('Guru Pengampu')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                TextInput::make('academic_year')->label('Tahun Ajaran')->required()->maxLength(20)
                    ->placeholder('2025/2026'),
                Select::make('semester')->label('Semester')->required()
                    ->options(['ganjil' => 'Ganjil', 'genap' => 'Genap']),
                Toggle::make('is_active')->label('Aktif')->default(true),
                TextInput::make('title')->label('Judul')->required()->maxLength(200)->columnSpanFull(),
                Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                TextInput::make('default_methods')->label('Metode Default')->maxLength(255)
                    ->placeholder('Ceramah, Diskusi, Praktik'),
                TextInput::make('default_media')->label('Media Default')->maxLength(255)
                    ->placeholder('LCD, Papan Tulis, Lab'),
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
                    ->formatStateUsing(fn ($s) => $s === 'ganjil' ? 'Ganjil' : 'Genap'),
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

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
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
