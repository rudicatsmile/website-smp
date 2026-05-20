<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ExamScore;
use App\Models\ExamSession;
use App\Models\LessonSession;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\SessionAssessmentScore;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LaporanPenilaianPdfController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']), 403);

        $request->validate([
            'class_id'     => 'required|integer',
            'subject_id'   => 'required|integer',
            'academic_year'=> 'required|string',
        ]);

        $classId      = (int) $request->class_id;
        $subjectId    = (int) $request->subject_id;
        $academicYear = $request->academic_year;

        $class   = SchoolClass::findOrFail($classId);
        $subject = MaterialCategory::findOrFail($subjectId);

        // Academic year bounds
        $parts     = explode('/', $academicYear);
        $startYear = (int) ($parts[0] ?? now()->year);
        $yearStart = Carbon::createFromDate($startYear, 7, 1)->startOfDay();
        $yearEnd   = Carbon::createFromDate($startYear + 1, 6, 30)->endOfDay();

        // TP Sessions (only those with assessments)
        $tpSessions = LessonSession::with(['assessments.scores'])
            ->where('school_class_id', $classId)
            ->where('material_category_id', $subjectId)
            ->whereBetween('session_date', [$yearStart, $yearEnd])
            ->orderBy('session_date')
            ->get()
            ->filter(fn ($s) => $s->assessments->isNotEmpty())
            ->values();

        // Exam sessions
        $examSessions = ExamSession::where('school_class_id', $classId)
            ->where('material_category_id', $subjectId)
            ->where('academic_year', $academicYear)
            ->get();

        $presentExamTypes = collect(array_keys(ExamSession::TYPES))
            ->filter(fn ($type) => $examSessions->where('exam_type', $type)->isNotEmpty())
            ->values();

        // Students
        $students   = Student::active()->where('school_class_id', $classId)->orderBy('name')->get();
        $studentIds = $students->pluck('id')->all();

        // Pre-load scores
        $allAssessmentIds = $tpSessions->flatMap(fn ($s) => $s->assessments->pluck('id'))->all();

        $asGrouped = SessionAssessmentScore::whereIn('student_id', $studentIds)
            ->whereIn('session_assessment_id', $allAssessmentIds)
            ->whereNotNull('score')
            ->get()
            ->groupBy('student_id')
            ->map(fn ($g) => $g->keyBy('session_assessment_id'));

        $examGrouped = ExamScore::whereIn('student_id', $studentIds)
            ->whereIn('exam_session_id', $examSessions->pluck('id')->all())
            ->whereNotNull('score')
            ->get()
            ->groupBy('student_id')
            ->map(fn ($g) => $g->keyBy('exam_session_id'));

        // Build rows
        $rows = [];
        foreach ($students as $i => $student) {
            $sid             = $student->id;
            $studentAs       = $asGrouped->get($sid, collect());
            $studentEx       = $examGrouped->get($sid, collect());

            $tpScores = [];
            foreach ($tpSessions as $session) {
                $aids   = $session->assessments->pluck('id')->all();
                $scores = collect($aids)->map(fn ($a) => $studentAs->get($a)?->score)->filter(fn ($v) => $v !== null);
                $tpScores[$session->id] = $scores->isNotEmpty() ? round($scores->avg(), 1) : null;
            }

            $examScoresByType = [];
            foreach ($presentExamTypes as $type) {
                $typeSessions = $examSessions->where('exam_type', $type);
                $scores = $typeSessions->map(fn ($es) => $studentEx->get($es->id)?->score)->filter(fn ($v) => $v !== null);
                $examScoresByType[$type] = $scores->isNotEmpty() ? round($scores->avg(), 1) : null;
            }

            $sem1Values = [];
            $sem2Values = [];
            foreach ($tpSessions as $session) {
                $val = $tpScores[$session->id];
                if ($val === null) continue;
                Carbon::parse($session->session_date)->month >= 7 ? $sem1Values[] = $val : $sem2Values[] = $val;
            }
            foreach ($examSessions as $es) {
                $val = $studentEx->get($es->id)?->score;
                if ($val === null) continue;
                $es->semester === 'ganjil' ? $sem1Values[] = (float) $val : $sem2Values[] = (float) $val;
            }

            $rows[] = [
                'no'              => $i + 1,
                'student'         => $student,
                'tpScores'        => $tpScores,
                'examScoresByType'=> $examScoresByType,
                'sem1'            => ! empty($sem1Values) ? round(array_sum($sem1Values) / count($sem1Values), 1) : null,
                'sem2'            => ! empty($sem2Values) ? round(array_sum($sem2Values) / count($sem2Values), 1) : null,
            ];
        }

        $data = compact('class', 'subject', 'academicYear', 'tpSessions', 'presentExamTypes', 'rows');
        $data['typeLabels'] = ExamSession::TYPES;

        $pdf = Pdf::loadView('reports.laporan-penilaian-pdf', $data)
            ->setPaper('a3', 'landscape');

        return $pdf->download('laporan-penilaian-' . str_replace('/', '-', $academicYear) . '.pdf');
    }
}
