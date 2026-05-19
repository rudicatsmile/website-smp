<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\LessonSession;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StudentAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JurnalMengajarPdfController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']), 403);

        $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        $dateFrom            = $request->from;
        $dateTo              = $request->to;
        $materialCategoryId  = $request->material_category_id ?: null;
        $schoolClassId       = $request->school_class_id ?: null;
        $academicYear        = $request->academic_year ?: null;

        $sessions = LessonSession::query()
            ->with(['schoolClass', 'subject', 'teacher', 'planTopic'])
            ->where('session_date', '>=', $dateFrom)
            ->where('session_date', '<=', $dateTo)
            ->when($materialCategoryId, fn ($q) => $q->where('material_category_id', $materialCategoryId))
            ->when($schoolClassId,      fn ($q) => $q->where('school_class_id', $schoolClassId))
            ->when($academicYear,       fn ($q) => $q->whereHas('plan', fn ($pq) => $pq->where('academic_year', $academicYear)))
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        // Preload attendance counts
        $classIds = $sessions->pluck('school_class_id')->unique()->values();
        $dates    = $sessions->pluck('session_date')->map(fn ($d) => Carbon::parse($d)->toDateString())->unique()->values();

        $attendanceCounts = collect();
        if ($sessions->isNotEmpty()) {
            $attendanceCounts = StudentAttendance::query()
                ->selectRaw('student_id, date, status')
                ->whereIn('date', $dates)
                ->whereHas('student', fn ($q) => $q->whereIn('school_class_id', $classIds))
                ->whereIn('status', ['hadir', 'terlambat'])
                ->with('student:id,school_class_id')
                ->get()
                ->groupBy(fn ($a) => $a->student->school_class_id . '_' . Carbon::parse($a->date)->toDateString())
                ->map->count();
        }

        $rows = [];
        foreach ($sessions as $i => $session) {
            $dateKey = $session->school_class_id . '_' . Carbon::parse($session->session_date)->toDateString();
            $rows[] = [
                'no'           => $i + 1,
                'session'      => $session,
                'date_label'   => Carbon::parse($session->session_date)->isoFormat('dddd, D MMMM Y'),
                'week_number'  => $session->planTopic?->week_number,
                'topic'        => $session->topic,
                'hadir'        => $attendanceCounts->get($dateKey, 0),
                'notes'        => $session->notes,
                'class_name'   => $session->schoolClass?->name ?? '—',
                'subject_name' => $session->subject?->name ?? '—',
            ];
        }

        $pdf = Pdf::loadView('reports.jurnal-mengajar-pdf', [
            'rows'         => $rows,
            'class'        => $schoolClassId ? SchoolClass::find($schoolClassId) : null,
            'subject'      => $materialCategoryId ? MaterialCategory::find($materialCategoryId) : null,
            'academicYear' => $academicYear,
            'dateFrom'     => $dateFrom,
            'dateTo'       => $dateTo,
        ])->setPaper('a4', 'landscape');

        $filename = 'jurnal-mengajar-' . $dateFrom . '_' . $dateTo . '.pdf';

        return $pdf->download($filename);
    }
}
