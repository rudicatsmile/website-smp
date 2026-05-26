<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuranSurah extends Model
{
    protected $fillable = ['name', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'order' => 'integer'];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('order')->orderBy('name');
    }
}
