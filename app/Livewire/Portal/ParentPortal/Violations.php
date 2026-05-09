<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Pelanggaran')]
class Violations extends Component
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
        $items = $this->student->violations()->with('recorder')->orderByDesc('date')->get();
        $totalPoints = (int) $items->sum('points');

        return view('livewire.portal.parent.violations', [
            'items' => $items,
            'totalPoints' => $totalPoints,
        ]);
    }
}
