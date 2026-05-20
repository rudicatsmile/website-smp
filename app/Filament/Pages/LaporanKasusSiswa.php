<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Exports\LaporanKasusSiswaExport;
use App\Models\LessonSessionCase;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use BackedEnum;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKasusSiswa extends Page
{
    protected string $view = 'filament.pages.laporan-kasus-siswa';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Laporan Kasus Siswa';

    protected static ?string $title = 'Catatan Kasus Peserta Didik';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 28;

    public ?int   $school_class_id      = null;
    public ?int   $material_category_id = null;
    public string $date_from            = '';
    public string $date_to              = '';
    public bool   $show_report          = false;

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
            'school_class_id'      => 'required|integer',
            'material_category_id' => 'required|integer',
            'date_from'            => 'required|date',
            'date_to'              => 'required|date|after_or_equal:date_from',
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

    public function getReportDataProperty(): array
    {
        if (! $this->show_report || ! $this->school_class_id || ! $this->material_category_id) {
            return [];
        }

        $class   = SchoolClass::find($this->school_class_id);
        $subject = MaterialCategory::find($this->material_category_id);

        $cases = LessonSessionCase::with(['student', 'lessonSession.schoolClass'])
            ->whereHas('lessonSession', function ($q) {
                $q->where('school_class_id', $this->school_class_id)
                  ->where('material_category_id', $this->material_category_id)
                  ->whereBetween('session_date', [$this->date_from, $this->date_to]);
            })
            ->get()
            ->sortBy(fn ($c) => $c->lessonSession?->session_date)
            ->values();

        $rows = $cases->map(function ($case, $i) {
            return [
                'no'         => $i + 1,
                'student'    => $case->student,
                'date'       => $case->lessonSession?->session_date,
                'class'      => $case->lessonSession?->schoolClass?->name ?? '—',
                'problem'    => $case->problem,
                'selesai'    => $case->status === 'selesai',
                'follow_up'  => $case->follow_up,
            ];
        })->all();

        return compact('rows', 'class', 'subject');
    }

    public function exportExcel()
    {
        if (! $this->show_report) return;
        $data     = $this->reportData;
        $filename = 'laporan-kasus-siswa-' . $this->date_from . '_' . $this->date_to . '.xlsx';
        return Excel::download(new LaporanKasusSiswaExport($data), $filename);
    }

    public function exportPdf()
    {
        if (! $this->show_report) return;
        return redirect()->route('laporan-kasus-siswa.pdf', [
            'class_id'    => $this->school_class_id,
            'subject_id'  => $this->material_category_id,
            'date_from'   => $this->date_from,
            'date_to'     => $this->date_to,
        ]);
    }
}
