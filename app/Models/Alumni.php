<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Alumni extends Model
{
    protected $table = 'alumni';

    protected $fillable = [
        'name', 'slug', 'photo', 'graduation_year',
        'current_status', 'company_or_institution', 'position',
        'city', 'linkedin_url', 'quote', 'story',
        'is_featured', 'is_published', 'order',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'is_featured'     => 'boolean',
        'is_published'    => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    public function getCurrentStatusLabelAttribute(): string
    {
        return match ($this->current_status) {
            'working'      => 'Bekerja',
            'studying'     => 'Kuliah',
            'entrepreneur' => 'Wirausaha',
            'both'         => 'Kuliah & Bekerja',
            default        => 'Lainnya',
        };
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeOrderedDefault(Builder $q): Builder
    {
        return $q->orderBy('order')->orderByDesc('graduation_year');
    }
}
