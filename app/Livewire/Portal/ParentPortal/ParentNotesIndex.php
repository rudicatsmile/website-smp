<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\ParentNote;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Buku Penghubung')]
class ParentNotesIndex extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('parent'), 403);
        abort_unless($user->children()->whereKey($student->id)->exists(), 403, 'Bukan anak Anda.');
        $this->student = $student;
    }

    public function render()
    {
        $notes = ParentNote::query()
            ->where('student_id', $this->student->id)
            ->withCount(['messages as messages_count' => fn ($q) => $q->where('is_internal', false)])
            ->withCount(['messages as unread_count' => fn ($q) => $q
                ->where('is_internal', false)
                ->where('sender_type', 'teacher')
                ->whereNull('read_at')
            ])
            ->with(['homeroomTeacher', 'schoolClass'])
            ->orderByDesc('last_activity_at')
            ->get();

        return view('livewire.portal.parent.parent-notes-index', [
            'notes' => $notes,
        ]);
    }
}
