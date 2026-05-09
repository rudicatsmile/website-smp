<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Quiz;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
#[Title('Latihan & Kuis')]
class QuizIndex extends Component
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

        $query = Quiz::query()
            ->published()
            ->forStudent($student)
            ->with(['subject', 'teacher', 'attempts' => fn ($q) => $q->where('student_id', $student->id)])
            ->withCount('questions');

        if ($this->status === 'available') {
            $query->activeNow();
        } elseif ($this->status === 'finished') {
            $query->whereHas('attempts', fn ($q) => $q->where('student_id', $student->id)->whereNotNull('submitted_at'));
        } elseif ($this->status === 'closed') {
            $query->whereNotNull('closes_at')->where('closes_at', '<', now());
        }

        $quizzes = $query->orderByDesc('opens_at')->orderByDesc('created_at')->paginate(10);

        return view('livewire.portal.quiz-index', compact('quizzes', 'student'));
    }
}
