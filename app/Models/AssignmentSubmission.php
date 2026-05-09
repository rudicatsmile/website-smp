<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    protected $fillable = [
        'class_assignment_id', 'student_id',
        'files', 'note', 'submitted_at',
        'score', 'feedback', 'graded_at', 'graded_by',
    ];

    protected $casts = [
        'files' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'score' => 'integer',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ClassAssignment::class, 'class_assignment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'graded_by');
    }

    public function getIsLateAttribute(): bool
    {
        return $this->submitted_at
            && $this->assignment?->due_at
            && $this->submitted_at->gt($this->assignment->due_at);
    }

    public function getIsGradedAttribute(): bool
    {
        return $this->score !== null;
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_graded) {
            return 'graded';
        }
        if ($this->submitted_at) {
            return $this->is_late ? 'late' : 'submitted';
        }

        return 'draft';
    }
}
