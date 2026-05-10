<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Settings\GeneralSettings;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CetakKartuSiswa extends Page
{
    protected string $view = 'filament.pages.cetak-kartu-siswa';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Cetak Kartu Siswa';

    protected static ?string $title = 'Cetak Kartu Siswa';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 6;

    public ?int $school_class_id = null;

    /** @var array<int> */
    public array $student_ids = [];

    public bool $showPreview = false;

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $this->school_class_id = SchoolClass::where('is_active', true)->orderBy('grade')->orderBy('section')->value('id');
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function setClass(?int $id): void
    {
        $this->school_class_id = $id;
        $this->student_ids = [];
        $this->showPreview = false;
    }

    public function toggleStudent(int $id): void
    {
        if (in_array($id, $this->student_ids, true)) {
            $this->student_ids = array_values(array_diff($this->student_ids, [$id]));
        } else {
            $this->student_ids[] = $id;
        }
    }

    public function selectAll(): void
    {
        $this->student_ids = $this->classStudents->pluck('id')->all();
    }

    public function clearSelection(): void
    {
        $this->student_ids = [];
    }

    public function preview(): void
    {
        $this->showPreview = count($this->student_ids) > 0;
    }

    public function getClassStudentsProperty()
    {
        if (! $this->school_class_id) return collect();
        return Student::active()
            ->where('school_class_id', $this->school_class_id)
            ->orderBy('name')->get();
    }

    public function getSelectedStudentsProperty()
    {
        if (empty($this->student_ids)) return collect();
        return Student::with('schoolClass')
            ->whereIn('id', $this->student_ids)
            ->orderBy('name')->get();
    }

    public static function makeQrDataUri(string $token): string
    {
        $svg = (string) QrCode::format('svg')->size(200)->margin(0)->errorCorrection('M')->generate($token);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function downloadPdf(): StreamedResponse
    {
        $students = $this->selectedStudents;
        abort_if($students->isEmpty(), 404);

        // pastikan semua punya token
        $students->each(function (Student $s) {
            if (! $s->qr_token) $s->generateQrToken();
        });

        $settings = app(GeneralSettings::class);
        $cards = $students->map(fn (Student $s) => [
            'student' => $s,
            'qr' => static::makeQrDataUri($s->qr_token),
        ]);

        $pdf = Pdf::loadView('pdf.kartu-siswa', [
            'cards' => $cards,
            'settings' => $settings,
        ])->setPaper('a4');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'kartu-siswa.pdf'
        );
    }
}
