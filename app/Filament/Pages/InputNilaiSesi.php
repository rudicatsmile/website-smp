<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\SessionAssessment;
use App\Models\SessionAssessmentScore;
use App\Models\Student;
use App\Models\StudentAttendance;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class InputNilaiSesi extends Page
{
    protected string $view = 'filament.pages.input-nilai-sesi';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static bool $shouldRegisterNavigation = false;

    public int    $assessmentId = 0;
    public array  $scores       = [];   // [student_id => ['score' => '', 'notes' => '']]
    public bool   $saved        = false;

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->assessmentId = (int) request()->query('assessment', 0);
        abort_if($this->assessmentId === 0, 404);
        $this->loadScores();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public function getAssessmentProperty(): ?SessionAssessment
    {
        return SessionAssessment::with(['lessonSession.schoolClass', 'lessonSession.subject', 'scores.student'])
            ->find($this->assessmentId);
    }

    public function getStudentsProperty(): Collection
    {
        $assessment = $this->assessment;
        if (! $assessment) return collect();

        $session = $assessment->lessonSession;
        $date    = $session?->session_date?->toDateString();
        $classId = $session?->school_class_id;

        if (! $classId) return collect();

        // Students who attended (hadir or terlambat)
        $presentIds = $date
            ? StudentAttendance::query()
                ->where('date', $date)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->whereHas('student', fn ($q) => $q->where('school_class_id', $classId))
                ->pluck('student_id')
                ->toArray()
            : [];

        // All active students in class
        $all = Student::active()
            ->where('school_class_id', $classId)
            ->orderBy('name')
            ->get();

        // Mark each student as present/absent
        return $all->map(function ($student) use ($presentIds, $date) {
            $student->is_present  = $date ? in_array($student->id, $presentIds) : true;
            $student->no_absensi  = ! $date;
            return $student;
        });
    }

    public function getHasAbsensiProperty(): bool
    {
        $assessment = $this->assessment;
        if (! $assessment) return false;

        $session = $assessment->lessonSession;
        $date    = $session?->session_date?->toDateString();
        $classId = $session?->school_class_id;

        if (! $date || ! $classId) return false;

        return StudentAttendance::query()
            ->where('date', $date)
            ->whereHas('student', fn ($q) => $q->where('school_class_id', $classId))
            ->exists();
    }

    private function loadScores(): void
    {
        $existing = SessionAssessmentScore::where('session_assessment_id', $this->assessmentId)
            ->get()
            ->keyBy('student_id');

        foreach ($this->students as $student) {
            $rec = $existing->get($student->id);
            $this->scores[$student->id] = [
                'score' => $rec ? (string) $rec->score : '',
                'notes' => $rec?->notes ?? '',
            ];
        }
    }

    public function save(): void
    {
        $assessment = $this->assessment;
        abort_if(! $assessment, 404);

        foreach ($this->scores as $studentId => $data) {
            $score = $data['score'] !== '' ? (float) $data['score'] : null;

            if ($score !== null && $score > (float) $assessment->max_score) {
                Notification::make()
                    ->title('Nilai melebihi maksimal (' . $assessment->max_score . ')')
                    ->warning()
                    ->send();
                return;
            }

            SessionAssessmentScore::updateOrCreate(
                ['session_assessment_id' => $this->assessmentId, 'student_id' => $studentId],
                ['score' => $score, 'notes' => $data['notes'] ?? null],
            );
        }

        $this->saved = true;

        Notification::make()
            ->title('Nilai berhasil disimpan')
            ->success()
            ->send();
    }

    public function backUrl(): string
    {
        $session = $this->assessment?->lessonSession;
        if ($session) {
            return route('filament.admin.resources.lesson-sessions.edit', ['record' => $session->id]);
        }
        return route('filament.admin.resources.lesson-sessions.index');
    }
}
