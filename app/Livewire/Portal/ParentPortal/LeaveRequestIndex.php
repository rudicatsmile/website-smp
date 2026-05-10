<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Surat Izin')]
class LeaveRequestIndex extends Component
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
        $items = $this->student->leaveRequests()
            ->with('reviewer')
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.portal.parent.leave-request-index', [
            'items' => $items,
        ]);
    }
}
