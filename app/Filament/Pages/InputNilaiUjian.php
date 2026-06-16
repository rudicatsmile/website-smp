<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\ExamScore;
use App\Models\ExamSession;
use App\Models\Student;
use App\Models\StudentAttendance;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class InputNilaiUjian extends Page
{
    use HasPageShield;
    protected string $view = 'filament.pages.input-nilai-ujian';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static bool $shouldRegisterNavigation = false;

    public int   $examId = 0;
    public array $scores = [];   // [student_id => ['score' => '', 'is_remedial' => false, 'notes' => '']]

    public function mount(): void
    {
        $this->examId = (int) request()->query('exam', 0);
        abort_if($this->examId === 0, 404);
        $this->loadScores();
    }

    public function getExamProperty(): ?ExamSession
    {
        return ExamSession::with(['schoolClass', 'subject', 'teacher', 'scores.student'])
            ->find($this->examId);
    }

    public function getStudentsProperty(): Collection
    {
        $exam = $this->exam;
        if (! $exam) return collect();

        $classId  = $exam->school_class_id;
        $examDate = $exam->exam_date?->toDateString();

        // Presence data on exam date (optional — may not exist)
        $presentIds = $examDate
            ? StudentAttendance::query()
                ->where('date', $examDate)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->whereHas('student', fn ($q) => $q->where('school_class_id', $classId))
                ->pluck('student_id')
                ->toArray()
            : null;

        $absentIds = $examDate
            ? StudentAttendance::query()
                ->where('date', $examDate)
                ->where('status', 'alpa')
                ->whereHas('student', fn ($q) => $q->where('school_class_id', $classId))
                ->pluck('student_id')
                ->toArray()
            : null;

        return Student::active()
            ->where('school_class_id', $classId)
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($presentIds, $absentIds) {
                // null = no attendance data, true = present, false = absent
                if ($presentIds === null) {
                    $student->attendance_status = null;
                } elseif (in_array($student->id, $presentIds)) {
                    $student->attendance_status = 'hadir';
                } elseif (in_array($student->id, $absentIds ?? [])) {
                    $student->attendance_status = 'alpa';
                } else {
                    $student->attendance_status = 'lain';
                }
                return $student;
            });
    }

    private function loadScores(): void
    {
        $existing = ExamScore::where('exam_session_id', $this->examId)
            ->get()
            ->keyBy('student_id');

        foreach ($this->students as $student) {
            $rec = $existing->get($student->id);
            $this->scores[$student->id] = [
                'score'       => $rec ? (string) $rec->score : '',
                'is_remedial' => $rec?->is_remedial ?? false,
                'notes'       => $rec?->notes ?? '',
            ];
        }
    }

    public function save(): void
    {
        $exam = $this->exam;
        abort_if(! $exam, 404);

        foreach ($this->scores as $studentId => $data) {
            $score = $data['score'] !== '' ? (float) $data['score'] : null;

            if ($score !== null && $score > (float) $exam->max_score) {
                Notification::make()
                    ->title('Nilai melebihi maksimal (' . $exam->max_score . ')')
                    ->warning()
                    ->send();
                return;
            }

            ExamScore::updateOrCreate(
                ['exam_session_id' => $this->examId, 'student_id' => $studentId],
                [
                    'score'       => $score,
                    'is_remedial' => (bool) ($data['is_remedial'] ?? false),
                    'notes'       => $data['notes'] ?? null,
                ],
            );
        }

        Notification::make()
            ->title('Nilai ujian berhasil disimpan')
            ->success()
            ->send();
    }

    public function backUrl(): string
    {
        return route('filament.admin.resources.exam-sessions.index');
    }
}
