<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'attempt_no',
        'started_at', 'submitted_at',
        'score', 'max_score',
        'is_graded', 'graded_by', 'graded_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'is_graded' => 'boolean',
        'score' => 'integer',
        'max_score' => 'integer',
        'attempt_no' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'graded_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function getIsInProgressAttribute(): bool
    {
        return $this->started_at && ! $this->submitted_at;
    }

    public function getIsFinishedAttribute(): bool
    {
        return (bool) $this->submitted_at;
    }

    public function getDeadlineAtAttribute(): ?\Carbon\CarbonInterface
    {
        if (! $this->started_at || ! $this->quiz?->duration_minutes) {
            return null;
        }
        return $this->started_at->copy()->addMinutes($this->quiz->duration_minutes);
    }

    public function getTimeRemainingAttribute(): ?int
    {
        $deadline = $this->deadline_at;
        if (! $deadline) return null;
        return max(0, now()->diffInSeconds($deadline, false));
    }

    public function getNeedsGradingAttribute(): bool
    {
        return $this->submitted_at && ! $this->is_graded;
    }
}
