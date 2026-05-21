<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CurriculumPlan extends Model
{
    use LogsActivity;

    protected $fillable = [
        'school_class_id', 'material_category_id', 'staff_member_id',
        'academic_year', 'semester',
        'title', 'time_allocation',
        'learning_objective_ids', 'learning_model_ids',
        'default_methods', 'default_media', 'default_media_other',
        'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active'              => 'boolean',
        'learning_objective_ids' => 'array',
        'learning_model_ids'     => 'array',
        'default_methods'        => 'array',
        'default_media'          => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'time_allocation', 'is_active', 'staff_member_id', 'learning_objective_ids', 'learning_model_ids', 'default_methods', 'default_media'])
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(CurriculumPlanTopic::class)->orderBy('week_number')->orderBy('order');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(LessonSession::class);
    }
}
