<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Detail Kuis')]
class QuizShow extends Component
{
    public Quiz $quiz;

    public function mount(string $slug): void
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $this->quiz = Quiz::published()
            ->forStudent($student)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function start()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        if (! $this->quiz->is_open) {
            session()->flash('error', 'Kuis tidak dalam jendela waktu pengerjaan.');
            return null;
        }

        // resume in-progress attempt jika ada
        $existing = QuizAttempt::where('quiz_id', $this->quiz->id)
            ->where('student_id', $student->id)
            ->whereNotNull('started_at')
            ->whereNull('submitted_at')
            ->first();
        if ($existing) {
            return redirect()->route('portal.quizzes.play', [$this->quiz->slug, $existing->id]);
        }

        $usedAttempts = QuizAttempt::where('quiz_id', $this->quiz->id)
            ->where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->count();
        if ($usedAttempts >= $this->quiz->max_attempts) {
            session()->flash('error', 'Kesempatan mengerjakan sudah habis.');
            return null;
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $student->id,
            'attempt_no' => $usedAttempts + 1,
            'started_at' => now(),
            'max_score' => $this->quiz->total_score,
        ]);

        return redirect()->route('portal.quizzes.play', [$this->quiz->slug, $attempt->id]);
    }

    public function render()
    {
        $student = auth()->user()->student;
        $attempts = QuizAttempt::where('quiz_id', $this->quiz->id)
            ->where('student_id', $student->id)
            ->orderBy('attempt_no')
            ->get();

        return view('livewire.portal.quiz-show', [
            'quiz' => $this->quiz,
            'attempts' => $attempts,
            'usedAttempts' => $attempts->whereNotNull('submitted_at')->count(),
        ]);
    }
}
