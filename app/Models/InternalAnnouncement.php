<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalAnnouncement extends Model
{
    public const CATEGORIES = [
        'rapat' => 'Rapat',
        'surat_tugas' => 'Surat Tugas',
        'agenda_dinas' => 'Agenda Dinas',
        'umum' => 'Umum',
    ];

    public const PRIORITIES = [
        'normal' => 'Normal',
        'penting' => 'Penting',
        'urgent' => 'Urgent',
    ];

    public const PRIORITY_COLORS = [
        'normal' => 'gray',
        'penting' => 'warning',
        'urgent' => 'danger',
    ];

    public const TARGET_ROLES = [
        'semua_guru' => 'Semua Guru',
        'staf' => 'Staf',
        'wali_kelas' => 'Wali Kelas',
    ];

    protected $fillable = [
        'user_id', 'title', 'slug', 'body',
        'category', 'priority',
        'target_roles', 'target_staff_ids', 'attachments',
        'is_pinned', 'published_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'target_roles' => 'array',
            'target_staff_ids' => 'array',
            'attachments' => 'array',
            'is_pinned' => 'boolean',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acknowledgements(): HasMany
    {
        return $this->hasMany(InternalAnnouncementAcknowledgement::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopePinned(Builder $q): Builder
    {
        return $q->where('is_pinned', true);
    }

    public function scopePublished(Builder $q): Builder
    {
        $now = now();

        return $q->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->where(function ($qq) use ($now) {
                $qq->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            });
    }

    public function scopeForUser(Builder $q, ?User $user): Builder
    {
        if (! $user) {
            return $q->whereRaw('1 = 0');
        }

        $isTeacher = $user->hasRole('teacher');
        $staffId = optional($user->staffMember)->id;

        return $q->where(function ($qq) use ($user, $isTeacher, $staffId) {
            // Tanpa target = umum untuk semua role internal
            $qq->where(function ($base) {
                $base->whereNull('target_roles')->orWhere('target_roles', '[]');
            })->where(function ($base) {
                $base->whereNull('target_staff_ids')->orWhere('target_staff_ids', '[]');
            });

            // Target by role
            if ($isTeacher) {
                $qq->orWhereJsonContains('target_roles', 'semua_guru');
            }
            if ($user->hasAnyRole(['admin', 'editor', 'super_admin']) || $staffId) {
                $qq->orWhereJsonContains('target_roles', 'staf');
            }

            // Target by specific staff id
            if ($staffId) {
                $qq->orWhereJsonContains('target_staff_ids', $staffId);
            }
        });
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst((string) $this->category);
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? ucfirst((string) $this->priority);
    }

    public function getPriorityColorAttribute(): string
    {
        return self::PRIORITY_COLORS[$this->priority] ?? 'gray';
    }

    public function isAcknowledgedBy(User $user): bool
    {
        return $this->acknowledgements()->where('user_id', $user->id)->exists();
    }
}
