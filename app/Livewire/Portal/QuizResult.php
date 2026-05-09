<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Hasil Kuis')]
class QuizResult extends Component
{
    public Quiz $quiz;
    public QuizAttempt $attempt;

    public function mount(string $slug, int $attempt): void
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $this->quiz = Quiz::where('slug', $slug)->firstOrFail();
        $this->attempt = QuizAttempt::with(['answers.question.options'])
            ->where('id', $attempt)
            ->where('quiz_id', $this->quiz->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        abort_unless($this->attempt->submitted_at, 404);
    }

    public function render()
    {
        return view('livewire.portal.quiz-result', [
            'quiz' => $this->quiz,
            'attempt' => $this->attempt,
        ]);
    }
}
