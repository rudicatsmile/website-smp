<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffMember extends Model
{
    protected $fillable = [
        'user_id',
        'name', 'slug', 'nip', 'nuptk', 'gender', 'birth_place', 'birth_date',
        'staff_category_id', 'position', 'is_principal', 'joined_at', 'years_of_service',
        'subjects', 'education', 'certifications', 'experiences',
        'email', 'phone', 'whatsapp', 'social',
        'photo', 'bio', 'quote',
        'order', 'is_active',
    ];

    protected $casts = [
        'gender' => 'string',
        'birth_date' => 'date',
        'is_principal' => 'boolean',
        'joined_at' => 'date',
        'years_of_service' => 'integer',
        'subjects' => 'array',
        'education' => 'array',
        'certifications' => 'array',
        'experiences' => 'array',
        'social' => 'array',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StaffCategory::class, 'staff_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StaffSchedule::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopePrincipal(Builder $q): Builder
    {
        return $q->where('is_principal', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('order')->orderBy('name');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}
