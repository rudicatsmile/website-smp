<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Kerjakan Kuis')]
class QuizPlay extends Component
{
    public Quiz $quiz;
    public QuizAttempt $attempt;
    public array $answers = []; // [quiz_question_id => ['option_ids' => [...], 'essay' => '']]
    public array $orderedQuestionIds = [];
    public array $optionOrder = []; // [quiz_question_id => [option_ids in display order]]
    public int $currentIndex = 0;

    public function mount(string $slug, int $attempt): void
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $this->quiz = Quiz::with(['questions.options'])
            ->where('slug', $slug)
            ->firstOrFail();

        $this->attempt = QuizAttempt::where('id', $attempt)
            ->where('quiz_id', $this->quiz->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($this->attempt->submitted_at) {
            redirect()->route('portal.quizzes.result', [$this->quiz->slug, $this->attempt->id])->send();
            return;
        }

        // Build deterministic order per attempt (seed by attempt id)
        $questions = $this->quiz->questions->all();
        if ($this->quiz->shuffle_questions) {
            mt_srand($this->attempt->id);
            usort($questions, fn ($a, $b) => mt_rand(-1, 1));
        }
        $this->orderedQuestionIds = array_map(fn ($q) => $q->id, $questions);

        foreach ($this->quiz->questions as $q) {
            $opts = $q->options->all();
            if ($this->quiz->shuffle_options && $q->type !== 'essay') {
                mt_srand($this->attempt->id * 1000 + $q->id);
                usort($opts, fn ($a, $b) => mt_rand(-1, 1));
            }
            $this->optionOrder[$q->id] = array_map(fn ($o) => $o->id, $opts);
        }

        // Load existing answers
        $existing = $this->attempt->answers()->get()->keyBy('quiz_question_id');
        foreach ($this->orderedQuestionIds as $qid) {
            $a = $existing->get($qid);
            $this->answers[$qid] = [
                'option_ids' => $a?->selected_option_ids ?? [],
                'essay' => $a?->essay_text ?? '',
            ];
        }
    }

    public function selectOption(int $questionId, int $optionId, bool $multi): void
    {
        $current = $this->answers[$questionId]['option_ids'] ?? [];
        if ($multi) {
            if (in_array($optionId, $current, true)) {
                $current = array_values(array_filter($current, fn ($id) => $id !== $optionId));
            } else {
                $current[] = $optionId;
            }
        } else {
            $current = [$optionId];
        }
        $this->answers[$questionId]['option_ids'] = $current;
        $this->saveAnswer($questionId);
    }

    public function updatedAnswers($value, $key): void
    {
        // key e.g. "123.essay" — Livewire dot notation
        $parts = explode('.', $key);
        if (count($parts) >= 1) {
            $qid = (int) $parts[0];
            if ($qid > 0) {
                $this->saveAnswer($qid);
            }
        }
    }

    public function saveAnswer(int $questionId): void
    {
        $data = $this->answers[$questionId] ?? null;
        if (! $data) return;

        QuizAnswer::updateOrCreate(
            ['quiz_attempt_id' => $this->attempt->id, 'quiz_question_id' => $questionId],
            [
                'selected_option_ids' => $data['option_ids'] ?? [],
                'essay_text' => $data['essay'] ?? null,
            ],
        );
    }

    public function go(int $index): void
    {
        $this->currentIndex = max(0, min(count($this->orderedQuestionIds) - 1, $index));
    }

    public function submit()
    {
        // Persist any pending answers
        foreach ($this->orderedQuestionIds as $qid) {
            $this->saveAnswer($qid);
        }

        // Score auto-gradable
        $questions = $this->quiz->questions->keyBy('id');
        $totalScore = 0;
        $hasUngraded = false;

        foreach ($this->attempt->answers()->get() as $answer) {
            $q = $questions->get($answer->quiz_question_id);
            if (! $q) continue;

            if ($q->type === 'essay') {
                // leave to manual grading
                $hasUngraded = true;
                continue;
            }

            $correctIds = $q->options()->where('is_correct', true)->pluck('id')->sort()->values()->all();
            $selected = collect($answer->selected_option_ids ?? [])->sort()->values()->all();

            $isCorrect = false;
            if ($q->type === 'mcq') {
                $isCorrect = count($selected) === 1 && count($correctIds) === 1 && $selected[0] === $correctIds[0];
            } elseif ($q->type === 'multi') {
                $isCorrect = $selected === $correctIds && count($selected) > 0;
            }

            $awarded = $isCorrect ? (int) $q->score : 0;
            $totalScore += $awarded;

            $answer->update([
                'is_correct' => $isCorrect,
                'score_awarded' => $awarded,
            ]);
        }

        $this->attempt->update([
            'submitted_at' => now(),
            'score' => $totalScore,
            'is_graded' => ! $hasUngraded,
            'graded_at' => $hasUngraded ? null : now(),
        ]);

        return redirect()->route('portal.quizzes.result', [$this->quiz->slug, $this->attempt->id]);
    }

    public function render()
    {
        $questions = $this->quiz->questions->keyBy('id');
        return view('livewire.portal.quiz-play', [
            'questions' => $questions,
            'orderedIds' => $this->orderedQuestionIds,
            'currentId' => $this->orderedQuestionIds[$this->currentIndex] ?? null,
        ]);
    }
}
