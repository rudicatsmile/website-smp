<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassAnnouncement extends Model
{
    protected $fillable = [
        'school_class_id', 'staff_member_id',
        'title', 'slug', 'body', 'attachments',
        'pinned', 'is_published', 'published_at', 'expires_at',
        'notify_wa', 'notify_email', 'notification_sent_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'pinned' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'notify_wa' => 'boolean',
        'notify_email' => 'boolean',
        'notification_sent_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeActive(Builder $q): Builder
    {
        $now = now();

        return $q->where('is_published', true)
            ->where(function ($qq) use ($now) {
                $qq->whereNull('published_at')->orWhere('published_at', '<=', $now);
            })
            ->where(function ($qq) use ($now) {
                $qq->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            });
    }
}
