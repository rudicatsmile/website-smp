<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ParentNote extends Model
{
    protected $fillable = [
        'code', 'student_id', 'school_class_id', 'homeroom_teacher_id',
        'initiator_user_id', 'initiator_type',
        'subject', 'category', 'priority', 'status',
        'last_activity_at', 'resolved_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public const CATEGORIES = [
        'akademik' => 'Akademik / Belajar',
        'perilaku' => 'Perilaku / Sikap',
        'kehadiran' => 'Kehadiran',
        'kesehatan' => 'Kesehatan',
        'lainnya' => 'Lainnya',
    ];

    public const PRIORITIES = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
    ];

    public const STATUSES = [
        'open' => 'Terbuka',
        'replied' => 'Dibalas',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $note) {
            if (empty($note->code)) {
                $note->code = static::generateUniqueCode();
            }
            if (empty($note->last_activity_at)) {
                $note->last_activity_at = now();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'BP-' . strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());
        return $code;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'homeroom_teacher_id');
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ParentNoteMessage::class)->orderBy('created_at');
    }

    public function publicMessages(): HasMany
    {
        return $this->hasMany(ParentNoteMessage::class)
            ->where('is_internal', false)
            ->orderBy('created_at');
    }

    public function scopeOpen(Builder $q): Builder
    {
        return $q->whereIn('status', ['open', 'replied']);
    }

    public function scopeForParent(Builder $q, User $user): Builder
    {
        return $q->whereHas('student.parents', fn ($qq) => $qq->whereKey($user->id));
    }

    public function scopeForTeacher(Builder $q, StaffMember $staff): Builder
    {
        return $q->where('homeroom_teacher_id', $staff->id);
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

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => 'info',
            'replied' => 'warning',
            'resolved' => 'success',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            default => 'gray',
        };
    }
}
