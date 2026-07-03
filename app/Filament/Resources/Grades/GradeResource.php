<?php

declare(strict_types=1);

namespace App\Filament\Resources\Grades;

use App\Filament\Resources\Grades\Pages\CreateGrade;
use App\Filament\Resources\Grades\Pages\EditGrade;
use App\Filament\Resources\Grades\Pages\ListGrades;
use App\Models\ExamScore;
use App\Models\Grade;
use App\Models\SessionAssessmentScore;
use App\Models\StaffMember;
use App\Models\Student;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Concerns\HidesFromEkskulRole;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class GradeResource extends Resource
{
    use HidesFromEkskulRole;

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
                    ->color(fn ($state) => $state === 'ganjil' ? 'info' : 'warning'),
                TextColumn::make('nilai_akhir')->label('Akhir')->numeric(2)->sortable(),
                TextColumn::make('predikat')->label('Predikat')->badge()
                    ->color(fn ($state) => match ($state) {
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
                BulkActionGroup::make([
                    BulkAction::make('hitungOtomatis')
                        ->label('Hitung Otomatis')
                        ->icon('heroicon-o-calculator')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Hitung Nilai Otomatis')
                        ->modalDescription('Sistem menghitung nilai_tugas (rata-rata kuis/ulangan), nilai_uts, dan nilai_uas dari data penilaian, lalu nilai_akhir = 40% tugas + 30% UTS + 30% UAS.')
                        ->action(function (Collection $records) {
                            $updated = 0;
                            foreach ($records as $grade) {
                                $student = $grade->student;
                                if (! $student) continue;

                                // nilai_tugas: avg harian scores for this student+subject+semester
                                $avgTugas = SessionAssessmentScore::query()
                                    ->where('student_id', $student->id)
                                    ->whereHas('assessment.lessonSession.subject', fn ($q) => $q->where('name', $grade->subject))
                                    ->whereNotNull('score')
                                    ->avg('score');

                                // nilai_uts: latest UTS/PTS score
                                $utsScore = ExamScore::query()
                                    ->where('student_id', $student->id)
                                    ->where('is_remedial', false)
                                    ->whereHas('examSession', fn ($q) => $q
                                        ->whereIn('exam_type', ['uts', 'pts'])
                                        ->where('academic_year', $grade->academic_year)
                                        ->where('semester', $grade->semester)
                                        ->whereHas('subject', fn ($sq) => $sq->where('name', $grade->subject)))
                                    ->orderByDesc('created_at')
                                    ->value('score');

                                // nilai_uas: latest UAS/PAS score
                                $uasScore = ExamScore::query()
                                    ->where('student_id', $student->id)
                                    ->where('is_remedial', false)
                                    ->whereHas('examSession', fn ($q) => $q
                                        ->whereIn('exam_type', ['uas', 'pas'])
                                        ->where('academic_year', $grade->academic_year)
                                        ->where('semester', $grade->semester)
                                        ->whereHas('subject', fn ($sq) => $sq->where('name', $grade->subject)))
                                    ->orderByDesc('created_at')
                                    ->value('score');

                                $nilaiTugas = $avgTugas !== null ? round((float) $avgTugas, 2) : $grade->nilai_tugas;
                                $nilaiUts   = $utsScore  !== null ? round((float) $utsScore, 2) : $grade->nilai_uts;
                                $nilaiUas   = $uasScore  !== null ? round((float) $uasScore, 2) : $grade->nilai_uas;

                                $nilaiAkhir = null;
                                if ($nilaiTugas !== null && $nilaiUts !== null && $nilaiUas !== null) {
                                    $nilaiAkhir = round((float) $nilaiTugas * 0.4 + (float) $nilaiUts * 0.3 + (float) $nilaiUas * 0.3, 2);
                                }

                                $grade->update([
                                    'nilai_tugas' => $nilaiTugas,
                                    'nilai_uts'   => $nilaiUts,
                                    'nilai_uas'   => $nilaiUas,
                                    'nilai_akhir' => $nilaiAkhir,
                                    'predikat'    => $nilaiAkhir !== null ? Grade::calcPredikat($nilaiAkhir) : $grade->predikat,
                                ]);
                                $updated++;
                            }
                            Notification::make()
                                ->title("{$updated} nilai berhasil dihitung")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
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
