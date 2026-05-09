<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassAssignment extends Model
{
    protected $fillable = [
        'school_class_id', 'material_category_id', 'staff_member_id',
        'title', 'slug', 'description', 'attachments',
        'due_at', 'max_score', 'is_published', 'published_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'due_at' => 'datetime',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'max_score' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOpen(Builder $q): Builder
    {
        return $q->where(function ($qq) {
            $qq->whereNull('due_at')->orWhere('due_at', '>=', now());
        });
    }

    public function scopeOverdue(Builder $q): Builder
    {
        return $q->whereNotNull('due_at')->where('due_at', '<', now());
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_at && $this->due_at->isPast();
    }

    public function submissionFor(Student $student): ?AssignmentSubmission
    {
        return $this->submissions()->where('student_id', $student->id)->first();
    }
}
