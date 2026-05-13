<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Extracurricular extends Model
{
    protected $fillable = [
        'slug', 'name', 'category', 'description', 'cover',
        'quota', 'coach_id', 'location', 'is_active', 'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'quota'     => 'integer',
        'order'     => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover ? asset('storage/' . $this->cover) : null;
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'coach_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ExtracurricularSchedule::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ExtracurricularMember::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(ExtracurricularAchievement::class)->orderByDesc('achieved_at');
    }

    public function galleryItems(): HasMany
    {
        return $this->hasMany(ExtracurricularGalleryItem::class)->orderBy('order');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('order')->orderBy('name');
    }
}
