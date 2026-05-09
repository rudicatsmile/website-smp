<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $fillable = [
        'grade', 'section', 'name', 'academic_year',
        'homeroom_teacher_id', 'is_active', 'order',
    ];

    protected $casts = [
        'grade' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'homeroom_teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ClassAssignment::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(ClassAnnouncement::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ClassMaterial::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('grade')->orderBy('section');
    }
}
