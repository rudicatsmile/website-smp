<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AttendanceReportPdfController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']), 403);

        $request->validate([
            'from'     => 'required|date',
            'to'       => 'required|date|after_or_equal:from',
            'class_id' => 'required|integer|exists:school_classes,id',
        ]);

        $from    = Carbon::parse($request->from)->startOfDay();
        $to      = Carbon::parse($request->to)->endOfDay();
        $class   = SchoolClass::findOrFail($request->class_id);

        $dates = collect(CarbonPeriod::create($from, $to))
            ->map(fn (Carbon $d) => $d->toDateString())
            ->values()
            ->toArray();

        $students = Student::active()
            ->where('school_class_id', $class->id)
            ->orderBy('name')
            ->get();

        $attendances = StudentAttendance::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->whereBetween('date', [$request->from, $request->to])
            ->get()
            ->groupBy('student_id')
            ->map(fn ($recs) => $recs->keyBy(fn ($r) => Carbon::parse($r->date)->toDateString()));

        $totalDays = count($dates);
        $rows = [];

        foreach ($students as $i => $student) {
            $daily  = $attendances->get($student->id, collect());
            $totals = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpa' => 0, 'terlambat' => 0];

            foreach ($dates as $date) {
                $rec = $daily->get($date);
                if ($rec) {
                    $totals[$rec->status] = ($totals[$rec->status] ?? 0) + 1;
                }
            }

            $hadirCount = $totals['hadir'] + $totals['terlambat'];
            $persen     = $totalDays > 0 ? round($hadirCount / $totalDays * 100, 1) : 0;

            $rows[] = [
                'no'      => $i + 1,
                'student' => $student,
                'daily'   => $daily,
                'hadir'   => $hadirCount,
                'sakit'   => $totals['sakit'],
                'izin'    => $totals['izin'],
                'alpa'    => $totals['alpa'],
                'persen'  => $persen,
            ];
        }

        $pdf = Pdf::loadView('reports.attendance-pdf', [
            'class'    => $class,
            'dates'    => $dates,
            'rows'     => $rows,
            'dateFrom' => $request->from,
            'dateTo'   => $request->to,
        ])->setPaper('a4', 'landscape');

        $filename = 'laporan-absensi-' . $request->from . '_' . $request->to . '.pdf';

        return $pdf->download($filename);
    }
}
