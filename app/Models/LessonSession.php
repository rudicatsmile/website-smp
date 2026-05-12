<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LessonSession extends Model
{
    use LogsActivity, SoftDeletes;

    public const STATUSES = [
        'draft' => 'Draft',
        'published' => 'Published',
        'ongoing' => 'Sedang Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public const STATUS_COLORS = [
        'draft' => 'slate',
        'published' => 'info',
        'ongoing' => 'warning',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];

    protected $fillable = [
        'school_class_id', 'material_category_id', 'staff_member_id',
        'curriculum_plan_id', 'curriculum_plan_topic_id',
        'session_date', 'start_time', 'end_time', 'period',
        'topic', 'learning_objectives', 'methods', 'media', 'assessment_plan',
        'status', 'notes',
        'actual_start_at', 'actual_end_at', 'achievement_percent',
        'execution_notes', 'homework_notes', 'student_work_links', 'issues_notes',
        'completed_at', 'cancelled_reason',
    ];

    protected $casts = [
        'session_date' => 'date',
        'achievement_percent' => 'integer',
        'actual_start_at' => 'datetime',
        'actual_end_at' => 'datetime',
        'completed_at' => 'datetime',
        'student_work_links' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['topic', 'status', 'session_date', 'start_time', 'end_time',
                'staff_member_id', 'achievement_percent', 'cancelled_reason'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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

    public function plan(): BelongsTo
    {
        return $this->belongsTo(CurriculumPlan::class, 'curriculum_plan_id');
    }

    public function planTopic(): BelongsTo
    {
        return $this->belongsTo(CurriculumPlanTopic::class, 'curriculum_plan_topic_id');
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(ClassMaterial::class, 'lesson_session_materials')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(ClassAssignment::class, 'lesson_session_assignments')
            ->withPivot('given_at')
            ->withTimestamps();
    }

    public function scopeForTeacher(Builder $q, StaffMember $staff): Builder
    {
        return $q->where('staff_member_id', $staff->id);
    }

    public function scopeForDate(Builder $q, $date): Builder
    {
        return $q->where('session_date', $date);
    }

    public function scopeToday(Builder $q): Builder
    {
        return $q->where('session_date', today());
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->whereIn('status', ['published', 'ongoing']);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function getIsTodayAttribute(): bool
    {
        return $this->session_date?->isToday() ?? false;
    }

    public function getTimeRangeAttribute(): string
    {
        $start = $this->start_time ? substr((string) $this->start_time, 0, 5) : '';
        $end = $this->end_time ? substr((string) $this->end_time, 0, 5) : '';
        return trim($start . ' – ' . $end, ' –');
    }
}
