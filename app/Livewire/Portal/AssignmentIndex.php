<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\ClassAssignment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
#[Title('Tugas Kelas')]
class AssignmentIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $status = 'all';

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $query = ClassAssignment::query()
            ->published()
            ->where('school_class_id', $student->school_class_id)
            ->with(['subject', 'teacher', 'submissions' => fn ($q) => $q->where('student_id', $student->id)]);

        if ($this->status === 'pending') {
            $query->where(function ($q) {
                $q->whereNull('due_at')->orWhere('due_at', '>=', now());
            })->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $student->id)->whereNotNull('submitted_at'));
        } elseif ($this->status === 'submitted') {
            $query->whereHas('submissions', fn ($q) => $q->where('student_id', $student->id)->whereNotNull('submitted_at'));
        } elseif ($this->status === 'overdue') {
            $query->whereNotNull('due_at')->where('due_at', '<', now())
                ->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $student->id)->whereNotNull('submitted_at'));
        }

        $assignments = $query->orderBy('due_at')->paginate(10);

        return view('livewire.portal.assignment-index', compact('assignments', 'student'));
    }
}
