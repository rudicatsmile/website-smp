<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CounselingTicket extends Model
{
    protected $fillable = [
        'code', 'user_id', 'reporter_name', 'reporter_contact',
        'category', 'priority', 'status',
        'subject', 'body', 'attachments',
        'channel', 'is_anonymous',
        'assigned_to', 'resolved_at', 'last_activity_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_anonymous' => 'boolean',
        'resolved_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    public const CATEGORIES = [
        'akademik' => 'Akademik / Belajar',
        'pribadi' => 'Pribadi',
        'keluarga' => 'Keluarga',
        'pertemanan' => 'Pertemanan / Bullying',
        'kesehatan' => 'Kesehatan / Mental',
        'ekonomi' => 'Ekonomi',
        'lainnya' => 'Lainnya',
    ];

    public const PRIORITIES = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
        'urgent' => 'Urgent',
    ];

    public const STATUSES = [
        'new' => 'Baru',
        'in_progress' => 'Ditangani',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $ticket) {
            if (empty($ticket->code)) {
                $ticket->code = static::generateUniqueCode();
            }
            if (empty($ticket->last_activity_at)) {
                $ticket->last_activity_at = now();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'BK-' . strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());
        return $code;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(CounselingMessage::class)->orderBy('created_at');
    }

    public function publicMessages(): HasMany
    {
        return $this->hasMany(CounselingMessage::class)->where('is_internal', false)->orderBy('created_at');
    }

    public function scopeOpen(Builder $q): Builder
    {
        return $q->whereIn('status', ['new', 'in_progress']);
    }

    public function scopeForUser(Builder $q, User $user): Builder
    {
        return $q->where('user_id', $user->id);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst((string) $this->category);
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? ucfirst((string) $this->priority);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            default => 'gray',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'new' => 'info',
            'in_progress' => 'warning',
            'resolved' => 'success',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
