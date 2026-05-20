<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\LessonSessionCase;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LaporanKasusSiswaPdfController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']), 403);

        $request->validate([
            'class_id'   => 'required|integer',
            'subject_id' => 'required|integer',
            'date_from'  => 'required|date',
            'date_to'    => 'required|date|after_or_equal:date_from',
        ]);

        $class   = SchoolClass::findOrFail($request->class_id);
        $subject = MaterialCategory::findOrFail($request->subject_id);

        $cases = LessonSessionCase::with(['student', 'lessonSession.schoolClass'])
            ->whereHas('lessonSession', function ($q) use ($request) {
                $q->where('school_class_id', $request->class_id)
                  ->where('material_category_id', $request->subject_id)
                  ->whereBetween('session_date', [$request->date_from, $request->date_to]);
            })
            ->get()
            ->sortBy(fn ($c) => $c->lessonSession?->session_date)
            ->values();

        $rows = $cases->map(function ($case, $i) {
            return [
                'no'        => $i + 1,
                'student'   => $case->student,
                'date'      => $case->lessonSession?->session_date,
                'class'     => $case->lessonSession?->schoolClass?->name ?? '—',
                'problem'   => $case->problem,
                'selesai'   => $case->status === 'selesai',
                'follow_up' => $case->follow_up,
            ];
        })->all();

        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;

        $pdf = Pdf::loadView('reports.laporan-kasus-siswa-pdf', compact('class', 'subject', 'rows', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-kasus-siswa-' . $dateFrom . '_' . $dateTo . '.pdf');
    }
}
