<?php

declare(strict_types=1);

namespace App\Filament\Tahfidz\Pages;

use App\Models\SchoolClass;
use App\Models\StaffMember;
use App\Models\TahfidzGrade;
use App\Models\TahfidzParticipant;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class InputNilai extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Input Nilai';
    protected static ?string $title = 'Input Nilai Tahfidz';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.tahfidz.pages.input-nilai';

    public ?int $school_class_id = null;
    public ?int $teacher_id = null;

    /** @var array<int, array{student_id: int, name: string, nis: string, surah: string, score: int|null, description: string}> */
    public array $rows = [];

    public function mount(): void
    {
        $staff = auth()->user()?->staffMember;
        if ($staff) {
            $this->teacher_id = $staff->id;
        }
    }

    public function loadStudents(): void
    {
        if (! $this->school_class_id) {
            $this->rows = [];
            return;
        }

        $participants = TahfidzParticipant::active()
            ->whereHas('student', fn ($q) => $q->where('school_class_id', $this->school_class_id))
            ->with(['student', 'grades'])
            ->get();

        $this->rows = $participants->map(function (TahfidzParticipant $p) {
            return [
                'student_id'  => $p->student_id,
                'name'        => $p->student->name,
                'nis'         => $p->student->nis ?? '',
                'surah'       => '',
                'score'       => null,
                'description' => '',
            ];
        })->toArray();
    }

    public function save(): void
    {
        $saved  = 0;
        $errors = [];

        foreach ($this->rows as $i => $row) {
            if (empty(trim($row['surah'] ?? ''))) {
                continue;
            }
            if (is_null($row['score']) || $row['score'] < 0 || $row['score'] > 100) {
                $errors[] = $row['name'] . ': nilai harus 0–100';
                continue;
            }

            TahfidzGrade::updateOrCreate(
                [
                    'student_id' => $row['student_id'],
                    'surah'      => trim($row['surah']),
                ],
                [
                    'teacher_id'  => $this->teacher_id,
                    'score'       => (int) $row['score'],
                    'description' => $row['description'] ?? null,
                ]
            );
            $saved++;
        }

        if ($errors) {
            Notification::make()
                ->title('Ada ' . count($errors) . ' baris gagal')
                ->body(implode("\n", $errors))
                ->warning()
                ->send();
        }

        if ($saved > 0) {
            Notification::make()
                ->title($saved . ' nilai berhasil disimpan')
                ->success()
                ->send();
        }
    }

    public function getClasses(): Collection
    {
        return SchoolClass::active()->ordered()->get();
    }

    public function getTeachers(): Collection
    {
        return StaffMember::active()->orderBy('name')->get();
    }
}
