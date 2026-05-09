<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'material_category_id', 'school_class_id', 'staff_member_id',
        'title', 'slug', 'description', 'scope',
        'duration_minutes', 'max_attempts',
        'shuffle_questions', 'shuffle_options',
        'show_explanation', 'show_score_immediately',
        'opens_at', 'closes_at', 'total_score',
        'is_published', 'published_at',
    ];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'show_explanation' => 'boolean',
        'show_score_immediately' => 'boolean',
        'is_published' => 'boolean',
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'published_at' => 'datetime',
        'duration_minutes' => 'integer',
        'max_attempts' => 'integer',
        'total_score' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeActiveNow(Builder $q): Builder
    {
        return $q->where(function ($qq) {
            $qq->whereNull('opens_at')->orWhere('opens_at', '<=', now());
        })->where(function ($qq) {
            $qq->whereNull('closes_at')->orWhere('closes_at', '>=', now());
        });
    }

    public function scopeForStudent(Builder $q, Student $student): Builder
    {
        return $q->where(function ($qq) use ($student) {
            $qq->where('scope', 'public')
               ->orWhere(function ($w) use ($student) {
                   $w->where('scope', 'assigned')->where('school_class_id', $student->school_class_id);
               });
        });
    }

    public function getIsOpenAttribute(): bool
    {
        $now = now();
        if ($this->opens_at && $this->opens_at->gt($now)) return false;
        if ($this->closes_at && $this->closes_at->lt($now)) return false;
        return $this->is_published;
    }
}
