<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAttendance;
use BackedEnum;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;

class LaporanAbsensi extends Page
{
    protected string $view = 'filament.pages.laporan-absensi';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'Laporan Absensi';

    protected static ?string $title = 'Laporan Absensi Siswa';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 22;

    public string $date_from   = '';
    public string $date_to     = '';
    public ?int   $school_class_id = null;
    public bool   $show_report = false;

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->date_from = now()->startOfMonth()->toDateString();
        $this->date_to   = now()->toDateString();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function generate(): void
    {
        $this->validate([
            'date_from'       => 'required|date',
            'date_to'         => 'required|date|after_or_equal:date_from',
            'school_class_id' => 'required|integer',
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

    public function getReportDataProperty(): array
    {
        if (! $this->show_report || ! $this->school_class_id) {
            return ['dates' => [], 'rows' => [], 'class' => null];
        }

        $from  = Carbon::parse($this->date_from)->startOfDay();
        $to    = Carbon::parse($this->date_to)->endOfDay();
        $class = SchoolClass::find($this->school_class_id);

        // All calendar dates in range
        $dates = collect(CarbonPeriod::create($from, $to))
            ->map(fn (Carbon $d) => $d->toDateString())
            ->values()
            ->toArray();

        // Students in class
        $students = Student::active()
            ->where('school_class_id', $this->school_class_id)
            ->orderBy('name')
            ->get();

        // All attendance in range for this class
        $attendances = StudentAttendance::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->whereBetween('date', [$this->date_from, $this->date_to])
            ->get()
            ->groupBy('student_id')
            ->map(fn ($recs) => $recs->keyBy(fn ($r) => Carbon::parse($r->date)->toDateString()));

        $totalDays = count($dates);
        $rows = [];

        foreach ($students as $i => $student) {
            $daily   = $attendances->get($student->id, collect());
            $totals  = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpa' => 0, 'terlambat' => 0];

            foreach ($dates as $date) {
                $rec = $daily->get($date);
                if ($rec) {
                    $totals[$rec->status] = ($totals[$rec->status] ?? 0) + 1;
                }
            }

            $hadirCount = $totals['hadir'] + $totals['terlambat'];
            $absenCount = $totals['sakit'] + $totals['izin'] + $totals['alpa'];
            $persen     = $totalDays > 0 ? round($hadirCount / $totalDays * 100, 1) : 0;

            $rows[] = [
                'no'       => $i + 1,
                'student'  => $student,
                'daily'    => $daily,
                'hadir'    => $hadirCount,
                'sakit'    => $totals['sakit'],
                'izin'     => $totals['izin'],
                'alpa'     => $totals['alpa'],
                'absen'    => $absenCount,
                'persen'   => $persen,
            ];
        }

        return [
            'dates'     => $dates,
            'rows'      => $rows,
            'class'     => $class,
            'total_days'=> $totalDays,
        ];
    }

    public function exportExcel()
    {
        if (! $this->show_report) return;

        $data = $this->reportData;
        $filename = 'laporan-absensi-' . $this->date_from . '_' . $this->date_to . '.xlsx';

        return Excel::download(
            new AttendanceReportExport($data, $this->date_from, $this->date_to),
            $filename
        );
    }

    public function exportPdf()
    {
        if (! $this->show_report) return;

        return redirect()->route('laporan-absensi.pdf', [
            'from'     => $this->date_from,
            'to'       => $this->date_to,
            'class_id' => $this->school_class_id,
        ]);
    }
}
