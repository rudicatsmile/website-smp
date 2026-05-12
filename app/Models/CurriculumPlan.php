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
        'title', 'description',
        'default_methods', 'default_media',
        'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'description', 'is_active', 'staff_member_id', 'default_methods', 'default_media'])
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
