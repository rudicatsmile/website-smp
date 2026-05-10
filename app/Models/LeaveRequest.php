<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LeaveRequest extends Model
{
    public const TYPES = [
        'sakit' => 'Sakit',
        'izin'  => 'Izin',
        'dinas' => 'Tugas/Dinas',
    ];

    public const STATUSES = [
        'pending'  => 'Menunggu Review',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    public const CHANNELS = [
        'portal' => 'Portal Orang Tua',
        'public' => 'Form Publik',
    ];

    protected $fillable = [
        'student_id', 'submitted_by_user_id',
        'type', 'date_from', 'date_to', 'reason', 'attachment',
        'status', 'submission_channel',
        'submitter_name', 'submitter_phone',
        'reviewed_by', 'reviewed_at', 'review_note',
        'notification_sent_at',
    ];

    protected $casts = [
        'date_from'             => 'date',
        'date_to'               => 'date',
        'reviewed_at'           => 'datetime',
        'notification_sent_at'  => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', 'pending');
    }

    public function scopeForStudent(Builder $q, int $studentId): Builder
    {
        return $q->where('student_id', $studentId);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst((string) $this->type);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getChannelLabelAttribute(): string
    {
        return self::CHANNELS[$this->submission_channel] ?? ucfirst((string) $this->submission_channel);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment ? Storage::disk('public')->url($this->attachment) : null;
    }

    public function getDateRangeLabelAttribute(): string
    {
        if (! $this->date_from || ! $this->date_to) {
            return '—';
        }
        if ($this->date_from->equalTo($this->date_to)) {
            return $this->date_from->translatedFormat('d M Y');
        }
        return $this->date_from->translatedFormat('d M Y') . ' — ' . $this->date_to->translatedFormat('d M Y');
    }

    public function getDayCountAttribute(): int
    {
        if (! $this->date_from || ! $this->date_to) {
            return 0;
        }
        return (int) $this->date_from->diffInDays($this->date_to) + 1;
    }

    public function getNoteTagAttribute(): string
    {
        return "Izin Online #{$this->id}";
    }
}
