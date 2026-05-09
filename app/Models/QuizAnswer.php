<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id', 'quiz_question_id',
        'selected_option_ids', 'essay_text',
        'is_correct', 'score_awarded', 'feedback',
    ];

    protected $casts = [
        'selected_option_ids' => 'array',
        'is_correct' => 'boolean',
        'score_awarded' => 'integer',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
