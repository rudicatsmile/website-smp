<?php

declare(strict_types=1);

namespace App\Filament\Resources\Grades;

use App\Filament\Resources\Grades\Pages\CreateGrade;
use App\Filament\Resources\Grades\Pages\EditGrade;
use App\Filament\Resources\Grades\Pages\ListGrades;
use App\Models\Grade;
use App\Models\StaffMember;
use App\Models\Student;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Nilai';

    protected static ?string $modelLabel = 'Nilai';

    protected static ?string $pluralModelLabel = 'Nilai';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Nilai')->columns(2)->schema([
                Select::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('staff_member_id')->label('Guru')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                TextInput::make('subject')->label('Mata Pelajaran')->required()->maxLength(100),
                TextInput::make('academic_year')->label('Tahun Ajaran')->required()
                    ->default('2025/2026')->maxLength(16),
                Select::make('semester')->label('Semester')
                    ->options(['ganjil' => 'Ganjil', 'genap' => 'Genap'])
                    ->default('ganjil')->required(),
            ]),
            Section::make('Nilai')->columns(4)->schema([
                TextInput::make('nilai_tugas')->label('Tugas')->numeric()->minValue(0)->maxValue(100)->step(0.01),
                TextInput::make('nilai_uts')->label('UTS')->numeric()->minValue(0)->maxValue(100)->step(0.01),
                TextInput::make('nilai_uas')->label('UAS')->numeric()->minValue(0)->maxValue(100)->step(0.01),
                TextInput::make('nilai_akhir')->label('Nilai Akhir')->numeric()->minValue(0)->maxValue(100)->step(0.01),
            ]),
            Section::make('Catatan')->schema([
                TextInput::make('predikat')->label('Predikat')->maxLength(4)
                    ->helperText('Isi otomatis jika kosong (A/B/C/D/E).'),
                Textarea::make('catatan_guru')->label('Catatan Guru')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->label('Siswa')->searchable()->sortable(),
                TextColumn::make('subject')->label('Mapel')->searchable(),
                TextColumn::make('academic_year')->label('T.A.')->toggleable(),
                TextColumn::make('semester')->label('Semester')->badge()
                    ->color(fn ($s) => $s === 'ganjil' ? 'info' : 'warning'),
                TextColumn::make('nilai_akhir')->label('Akhir')->numeric(2)->sortable(),
                TextColumn::make('predikat')->label('Predikat')->badge()
                    ->color(fn ($s) => match ($s) {
                        'A' => 'success', 'B' => 'info', 'C' => 'warning', default => 'danger',
                    }),
                TextColumn::make('teacher.name')->label('Guru')->toggleable()->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('semester')->options(['ganjil' => 'Ganjil', 'genap' => 'Genap']),
                SelectFilter::make('academic_year')->label('Tahun Ajaran')
                    ->options(fn () => Grade::query()->distinct()->pluck('academic_year', 'academic_year')->all()),
                SelectFilter::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGrades::route('/'),
            'create' => CreateGrade::route('/create'),
            'edit' => EditGrade::route('/{record}/edit'),
        ];
    }
}
