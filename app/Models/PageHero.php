<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageHero extends Model
{
    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'icon',
        'background_image',
        'overlay_from',
        'overlay_via',
        'overlay_to',
        'overlay_opacity',
        'show_breadcrumb',
        'is_active',
    ];

    protected $casts = [
        'overlay_opacity' => 'integer',
        'show_breadcrumb' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function forKey(string $key): ?self
    {
        return Cache::remember(
            "page_hero:{$key}",
            now()->addMinutes(30),
            fn () => self::query()->where('key', $key)->where('is_active', true)->first()
        );
    }

    protected static function booted(): void
    {
        static::saved(fn (self $m) => Cache::forget("page_hero:{$m->key}"));
        static::deleted(fn (self $m) => Cache::forget("page_hero:{$m->key}"));
    }
}
