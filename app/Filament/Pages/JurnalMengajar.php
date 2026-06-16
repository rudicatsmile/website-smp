<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Exports\JurnalMengajarExport;
use App\Models\CurriculumPlan;
use App\Models\LessonSession;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StudentAttendance;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class JurnalMengajar extends Page
{
    use HasPageShield;
    protected string $view = 'filament.pages.jurnal-mengajar';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Jurnal Mengajar';

    protected static ?string $title = 'Jurnal Mengajar Pendidik';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 25;

    public string  $date_from          = '';
    public string  $date_to            = '';
    public ?int    $material_category_id = null;
    public ?int    $school_class_id    = null;
    public string  $academic_year      = '';
    public bool    $show_report        = false;

    public function mount(): void
    {
        $this->date_from = now()->startOfMonth()->toDateString();
        $this->date_to   = now()->toDateString();
    }

    public function getClassesProperty()
    {
        return SchoolClass::active()->ordered()->get();
    }

    public function getSubjectsProperty()
    {
        return MaterialCategory::active()->ordered()->get();
    }

    public function getAcademicYearsProperty()
    {
        return CurriculumPlan::distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');
    }

    public function generate(): void
    {
        $this->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
        ]);

        $this->show_report = true;
    }

    public function reset_filter(): void
    {
        $this->show_report = false;
    }

    public function getReportDataProperty(): array
    {
        if (! $this->show_report) {
            return ['rows' => [], 'meta' => []];
        }

        $sessions = LessonSession::query()
            ->with(['schoolClass', 'subject', 'teacher', 'planTopic'])
            ->where('session_date', '>=', $this->date_from)
            ->where('session_date', '<=', $this->date_to)
            ->when($this->material_category_id, fn ($q) => $q->where('material_category_id', $this->material_category_id))
            ->when($this->school_class_id, fn ($q) => $q->where('school_class_id', $this->school_class_id))
            ->when($this->academic_year, fn ($q) => $q->whereHas('plan', fn ($pq) => $pq->where('academic_year', $this->academic_year)))
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        if ($sessions->isEmpty()) {
            return ['rows' => [], 'meta' => $this->buildMeta($sessions)];
        }

        // Preload attendance counts: group by (class_id, date)
        $classIds  = $sessions->pluck('school_class_id')->unique()->values();
        $dates     = $sessions->pluck('session_date')->map(fn ($d) => Carbon::parse($d)->toDateString())->unique()->values();

        $attendanceCounts = StudentAttendance::query()
            ->selectRaw('student_id, date, status')
            ->whereIn('date', $dates)
            ->whereHas('student', fn ($q) => $q->whereIn('school_class_id', $classIds))
            ->whereIn('status', ['hadir', 'terlambat'])
            ->with('student:id,school_class_id')
            ->get()
            ->groupBy(fn ($a) => $a->student->school_class_id . '_' . Carbon::parse($a->date)->toDateString())
            ->map->count();

        $rows = [];
        foreach ($sessions as $i => $session) {
            $dateKey = $session->school_class_id . '_' . Carbon::parse($session->session_date)->toDateString();

            $rows[] = [
                'no'          => $i + 1,
                'session'     => $session,
                'date_label'  => Carbon::parse($session->session_date)->isoFormat('dddd, D MMMM Y'),
                'week_number' => $session->planTopic?->week_number,
                'topic'       => $session->topic,
                'hadir'       => $attendanceCounts->get($dateKey, 0),
                'notes'       => $session->notes,
                'class_name'  => $session->schoolClass?->name ?? '—',
                'subject_name'=> $session->subject?->name ?? '—',
            ];
        }

        return ['rows' => $rows, 'meta' => $this->buildMeta($sessions)];
    }

    private function buildMeta($sessions): array
    {
        $class   = $this->school_class_id ? SchoolClass::find($this->school_class_id) : null;
        $subject = $this->material_category_id ? MaterialCategory::find($this->material_category_id) : null;

        return [
            'class'         => $class,
            'subject'       => $subject,
            'academic_year' => $this->academic_year ?: null,
            'total'         => $sessions->count(),
        ];
    }

    public function exportExcel()
    {
        if (! $this->show_report) return;

        $data = $this->reportData;
        $filename = 'jurnal-mengajar-' . $this->date_from . '_' . $this->date_to . '.xlsx';

        return Excel::download(new JurnalMengajarExport($data, $this->date_from, $this->date_to), $filename);
    }

    public function exportPdf()
    {
        if (! $this->show_report) return;

        return redirect()->route('jurnal-mengajar.pdf', [
            'from'                 => $this->date_from,
            'to'                   => $this->date_to,
            'material_category_id' => $this->material_category_id,
            'school_class_id'      => $this->school_class_id,
            'academic_year'        => $this->academic_year,
        ]);
    }
}
