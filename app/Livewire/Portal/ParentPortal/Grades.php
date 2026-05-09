<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Nilai Rapor')]
class Grades extends Component
{
    public Student $student;

    #[Url]
    public ?string $semester = null;

    #[Url]
    public ?string $academic_year = null;

    public function mount(Student $student): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('parent'), 403);
        abort_unless($user->children()->whereKey($student->id)->exists(), 403, 'Bukan anak Anda.');
        $this->student = $student;
    }

    public function render()
    {
        $years = $this->student->grades()->distinct()->pluck('academic_year')->sort()->values();
        $this->academic_year = $this->academic_year ?: ($years->last() ?? '2025/2026');
        $this->semester = $this->semester ?: 'ganjil';

        $grades = $this->student->grades()
            ->where('academic_year', $this->academic_year)
            ->where('semester', $this->semester)
            ->with('teacher')
            ->orderBy('subject')
            ->get();

        $avg = $grades->avg('nilai_akhir');

        return view('livewire.portal.parent.grades', [
            'grades' => $grades,
            'years' => $years,
            'avg' => $avg ? round((float) $avg, 2) : null,
        ]);
    }
}
