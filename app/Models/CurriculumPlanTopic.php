<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CurriculumPlanTopic extends Model
{
    use LogsActivity;

    protected $fillable = [
        'curriculum_plan_id', 'week_number', 'order',
        'topic', 'learning_objectives',
        'methods', 'media', 'assessment_plan',
        'default_duration_minutes', 'notes',
    ];

    protected $casts = [
        'week_number' => 'integer',
        'order' => 'integer',
        'default_duration_minutes' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['topic', 'learning_objectives', 'methods', 'media', 'assessment_plan', 'week_number', 'order'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(CurriculumPlan::class, 'curriculum_plan_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(LessonSession::class);
    }
}
