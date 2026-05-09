<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Leaderboard Kuis')]
class QuizLeaderboard extends Component
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

    public function render()
    {
        $student = auth()->user()->student;

        // Best attempt per student
        $rows = QuizAttempt::query()
            ->select('student_id')
            ->selectRaw('MAX(score) as best_score')
            ->selectRaw('MIN(submitted_at) as first_submitted_at')
            ->where('quiz_id', $this->quiz->id)
            ->whereNotNull('submitted_at')
            ->where('is_graded', true)
            ->groupBy('student_id')
            ->orderByDesc('best_score')
            ->orderBy('first_submitted_at')
            ->with('student.schoolClass')
            ->limit(20)
            ->get();

        // hydrate student relation manually since groupBy
        $studentIds = $rows->pluck('student_id')->all();
        $students = \App\Models\Student::with('schoolClass')->whereIn('id', $studentIds)->get()->keyBy('id');
        foreach ($rows as $r) {
            $r->setRelation('student', $students->get($r->student_id));
        }

        $myRank = null;
        foreach ($rows as $i => $r) {
            if ($r->student_id === $student->id) {
                $myRank = $i + 1;
                break;
            }
        }

        return view('livewire.portal.quiz-leaderboard', [
            'quiz' => $this->quiz,
            'rows' => $rows,
            'currentStudentId' => $student->id,
            'myRank' => $myRank,
        ]);
    }
}
