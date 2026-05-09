<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quizzes;

use App\Filament\Resources\Quizzes\Pages\CreateQuiz;
use App\Filament\Resources\Quizzes\Pages\EditQuiz;
use App\Filament\Resources\Quizzes\Pages\ListQuizzes;
use App\Filament\Resources\Quizzes\RelationManagers\AttemptsRelationManager;
use App\Filament\Resources\Quizzes\RelationManagers\QuestionsRelationManager;
use App\Models\MaterialCategory;
use App\Models\Quiz;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
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
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Kuis & Latihan';

    protected static ?string $modelLabel = 'Kuis';

    protected static ?string $pluralModelLabel = 'Kuis';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 15;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kuis')->columns(2)->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state . '-' . now()->format('YmdHis'))))
                    ->columnSpanFull(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->columnSpanFull(),
                Select::make('material_category_id')->label('Mata Pelajaran')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                Select::make('staff_member_id')->label('Guru Pengampu')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->default(fn () => auth()->user()?->staffMember?->id),
                Select::make('scope')->label('Lingkup Akses')
                    ->options(['assigned' => 'Per Kelas', 'public' => 'Terbuka (semua siswa)'])
                    ->default('assigned')->required()->live(),
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->visible(fn (callable $get) => $get('scope') === 'assigned')
                    ->required(fn (callable $get) => $get('scope') === 'assigned'),
                Textarea::make('description')->label('Deskripsi / Petunjuk')->rows(3)->columnSpanFull(),
            ]),
            Section::make('Pengaturan Pengerjaan')->columns(3)->schema([
                TextInput::make('duration_minutes')->label('Durasi (menit)')->numeric()->minValue(0)
                    ->helperText('Kosongkan jika tanpa batas waktu.'),
                TextInput::make('max_attempts')->label('Maks. Kesempatan')->numeric()->default(1)->minValue(1)->required(),
                TextInput::make('total_score')->label('Total Skor (auto)')->numeric()->disabled()->dehydrated(false),
                DateTimePicker::make('opens_at')->label('Buka Mulai')->native(false),
                DateTimePicker::make('closes_at')->label('Tutup Pada')->native(false),
                Toggle::make('shuffle_questions')->label('Acak urutan soal')->default(true),
                Toggle::make('shuffle_options')->label('Acak urutan opsi')->default(true),
                Toggle::make('show_explanation')->label('Tampilkan pembahasan')->default(true),
                Toggle::make('show_score_immediately')->label('Skor langsung muncul')->default(true),
            ]),
            Section::make('Publikasi')->columns(2)->schema([
                Toggle::make('is_published')->label('Terbitkan')->default(false),
                DateTimePicker::make('published_at')->label('Tanggal Terbit')->default(now())->native(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->limit(40),
                TextColumn::make('subject.name')->label('Mapel')->badge()->toggleable(),
                TextColumn::make('scope')->label('Akses')->badge()
                    ->formatStateUsing(fn ($s) => $s === 'public' ? 'Publik' : 'Per Kelas')
                    ->color(fn ($s) => $s === 'public' ? 'success' : 'info'),
                TextColumn::make('schoolClass.name')->label('Kelas')->badge()->placeholder('—'),
                TextColumn::make('questions_count')->label('Soal')->counts('questions')->badge(),
                TextColumn::make('total_score')->label('Total Skor')->badge(),
                TextColumn::make('attempts_count')->label('Attempt')->counts('attempts')->badge(),
                TextColumn::make('closes_at')->label('Tutup')->dateTime('d M Y H:i')->sortable()->toggleable(),
                IconColumn::make('is_published')->label('Terbit')->boolean(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('scope')->options(['assigned' => 'Per Kelas', 'public' => 'Publik']),
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::ordered()->pluck('name', 'id')),
                SelectFilter::make('material_category_id')->label('Mapel')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
            AttemptsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            $query->where('staff_member_id', $user->staffMember?->id);
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
            'index' => ListQuizzes::route('/'),
            'create' => CreateQuiz::route('/create'),
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }
}
