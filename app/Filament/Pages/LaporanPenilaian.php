<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Exports\LaporanPenilaianExport;
use App\Models\ExamSession;
use App\Models\ExamScore;
use App\Models\LessonSession;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\SessionAssessmentScore;
use App\Models\Student;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenilaian extends Page
{
    use HasPageShield;
    protected string $view = 'filament.pages.laporan-penilaian';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Penilaian';

    protected static ?string $title = 'Laporan Penilaian Peserta Didik';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 27;

    public ?int    $school_class_id       = null;
    public ?int    $material_category_id  = null;
    public string  $academic_year         = '';
    public bool    $show_report           = false;

    public function mount(): void
    {
        $this->academic_year = now()->month >= 7
            ? now()->year . '/' . (now()->year + 1)
            : (now()->year - 1) . '/' . now()->year;
    }

    public function generate(): void
    {
        $this->validate([
            'school_class_id'      => 'required|integer',
            'material_category_id' => 'required|integer',
            'academic_year'        => 'required|string|max:20',
        ]);
        $this->show_report = true;
    }

    public function reset_filter(): void
    {
        $this->show_report = false;
    }

    public function getClassesProperty()
    {
        return SchoolClass::active()->ordered()->get();
    }

    public function getSubjectsProperty()
    {
        return MaterialCategory::active()->ordered()->get();
    }

    /**
     * Build the full report matrix.
     *
     * Returns:
     *   tpSessions   – ordered LessonSession[] with ≥1 assessment
     *   examTypes    – ordered keys from ExamSession::TYPES that exist in filters
     *   rows         – per-student array with tpScores[], examScores[], sem1, sem2
     *   class        – SchoolClass model
     *   subject      – MaterialCategory model
     */
    public function getReportDataProperty(): array
    {
        if (! $this->show_report || ! $this->school_class_id || ! $this->material_category_id) {
            return [];
        }

        $class   = SchoolClass::find($this->school_class_id);
        $subject = MaterialCategory::find($this->material_category_id);

        // ── 1. Lesson sessions for this class + subject + academic year ──────────
        // We derive year boundaries from academic_year (e.g. "2025/2026" → 2025-07-01 … 2026-06-30)
        [$yearStart, $yearEnd] = $this->academicYearBounds();

        $tpSessions = LessonSession::with(['assessments.scores'])
            ->where('school_class_id', $this->school_class_id)
            ->where('material_category_id', $this->material_category_id)
            ->whereBetween('session_date', [$yearStart, $yearEnd])
            ->orderBy('session_date')
            ->get()
            ->filter(fn ($s) => $s->assessments->isNotEmpty())   // only sessions with assessments
            ->values();

        // ── 2. Exam sessions for same filters ────────────────────────────────────
        $examSessions = ExamSession::where('school_class_id', $this->school_class_id)
            ->where('material_category_id', $this->material_category_id)
            ->where('academic_year', $this->academic_year)
            ->get();

        // Which exam types actually exist → maintain TYPES order
        $presentExamTypes = collect(array_keys(ExamSession::TYPES))
            ->filter(fn ($type) => $examSessions->where('exam_type', $type)->isNotEmpty())
            ->values();

        // ── 3. Students ──────────────────────────────────────────────────────────
        $students = Student::active()
            ->where('school_class_id', $this->school_class_id)
            ->orderBy('name')
            ->get();

        if ($students->isEmpty()) {
            return [
                'tpSessions'    => $tpSessions,
                'examTypes'     => $presentExamTypes,
                'rows'          => [],
                'class'         => $class,
                'subject'       => $subject,
            ];
        }

        $studentIds = $students->pluck('id')->all();

        // ── 4. Pre-load all assessment scores for these students ─────────────────
        $allAssessmentIds = $tpSessions->flatMap(fn ($s) => $s->assessments->pluck('id'))->all();

        $assessmentScores = SessionAssessmentScore::whereIn('student_id', $studentIds)
            ->whereIn('session_assessment_id', $allAssessmentIds)
            ->whereNotNull('score')
            ->get();

        // Group: [student_id][assessment_id] => score
        $asGrouped = $assessmentScores
            ->groupBy('student_id')
            ->map(fn ($g) => $g->keyBy('session_assessment_id'));

        // ── 5. Pre-load all exam scores for these students ───────────────────────
        $examSessionIds = $examSessions->pluck('id')->all();

        $examScores = ExamScore::whereIn('student_id', $studentIds)
            ->whereIn('exam_session_id', $examSessionIds)
            ->whereNotNull('score')
            ->get()
            ->groupBy('student_id')
            ->map(fn ($g) => $g->keyBy('exam_session_id'));

        // ── 6. Build rows ────────────────────────────────────────────────────────
        $rows = [];

        foreach ($students as $i => $student) {
            $sid           = $student->id;
            $studentAsScores  = $asGrouped->get($sid, collect());
            $studentExScores  = $examScores->get($sid, collect());

            // TP scores per session (average of all assessments in that session)
            $tpScores = [];
            foreach ($tpSessions as $session) {
                $assessmentIds = $session->assessments->pluck('id')->all();
                $scores = collect($assessmentIds)
                    ->map(fn ($aid) => $studentAsScores->get($aid)?->score)
                    ->filter(fn ($v) => $v !== null)
                    ->values();
                $tpScores[$session->id] = $scores->isNotEmpty()
                    ? round($scores->avg(), 1)
                    : null;
            }

            // Exam scores per exam_type (average if multiple sessions of same type)
            $examScoresByType = [];
            foreach ($presentExamTypes as $type) {
                $typeSessions = $examSessions->where('exam_type', $type);
                $scores = $typeSessions
                    ->map(fn ($es) => $studentExScores->get($es->id)?->score)
                    ->filter(fn ($v) => $v !== null)
                    ->values();
                $examScoresByType[$type] = $scores->isNotEmpty()
                    ? round($scores->avg(), 1)
                    : null;
            }

            // Sumatif Akhir per semester
            // Semester ganjil (1) = Jul–Dec, genap (2) = Jan–Jun
            $sem1Values = [];
            $sem2Values = [];

            foreach ($tpSessions as $session) {
                $val = $tpScores[$session->id];
                if ($val === null) continue;
                $month = Carbon::parse($session->session_date)->month;
                if ($month >= 7) {
                    $sem1Values[] = $val;
                } else {
                    $sem2Values[] = $val;
                }
            }

            foreach ($examSessions as $es) {
                $val = $studentExScores->get($es->id)?->score;
                if ($val === null) continue;
                if ($es->semester === 'ganjil') {
                    $sem1Values[] = (float) $val;
                } else {
                    $sem2Values[] = (float) $val;
                }
            }

            $sem1 = ! empty($sem1Values) ? round(array_sum($sem1Values) / count($sem1Values), 1) : null;
            $sem2 = ! empty($sem2Values) ? round(array_sum($sem2Values) / count($sem2Values), 1) : null;

            $rows[] = [
                'no'             => $i + 1,
                'student'        => $student,
                'tpScores'       => $tpScores,
                'examScoresByType' => $examScoresByType,
                'sem1'           => $sem1,
                'sem2'           => $sem2,
            ];
        }

        return [
            'tpSessions'    => $tpSessions,
            'examTypes'     => $presentExamTypes,
            'rows'          => $rows,
            'class'         => $class,
            'subject'       => $subject,
        ];
    }

    /** Returns [Carbon $start, Carbon $end] for the selected academic year. */
    private function academicYearBounds(): array
    {
        $parts = explode('/', $this->academic_year);
        $startYear = (int) ($parts[0] ?? now()->year);
        return [
            Carbon::createFromDate($startYear, 7, 1)->startOfDay(),
            Carbon::createFromDate($startYear + 1, 6, 30)->endOfDay(),
        ];
    }

    public function exportExcel()
    {
        if (! $this->show_report) return;
        $data     = $this->reportData;
        $filename = 'laporan-penilaian-' . str_replace('/', '-', $this->academic_year) . '.xlsx';
        return Excel::download(new LaporanPenilaianExport($data), $filename);
    }

    public function exportPdf()
    {
        if (! $this->show_report) return;
        return redirect()->route('laporan-penilaian.pdf', [
            'class_id'    => $this->school_class_id,
            'subject_id'  => $this->material_category_id,
            'academic_year' => $this->academic_year,
        ]);
    }
}
