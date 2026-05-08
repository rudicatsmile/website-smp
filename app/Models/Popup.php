<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'title', 'content', 'image', 'link_url', 'link_text',
        'size', 'order', 'show_once', 'is_active', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'show_once' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeActive(Builder $q): Builder
    {
        $now = now();

        return $q->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now));
    }
}
