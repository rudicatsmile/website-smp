<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Raport Tahfidz')]
class TahfidzRaport extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $user = auth()->user();

        $isOwner   = $user && $user->student?->id === $student->id;
        $isParent  = $user && $student->parents->contains('id', $user->id);
        $isStaff   = $user && $user->hasAnyRole(['super_admin', 'admin', 'guru_pengampuh', 'teacher']);

        abort_unless($isOwner || $isParent || $isStaff, 403);

        $this->student = $student->load([
            'schoolClass',
            'tahfidzParticipant',
            'tahfidzGrades' => fn ($q) => $q->orderBy('surah')->with('teacher'),
        ]);
    }

    public function render()
    {
        return view('livewire.portal.tahfidz-raport');
    }
}
