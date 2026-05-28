<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LessonSessionCase extends Model
{
    use LogsActivity;

    public const STATUSES = [
        'tidak_selesai' => 'Tidak Selesai',
        'selesai'       => 'Selesai',
    ];

    public const STATUS_COLORS = [
        'tidak_selesai' => 'danger',
        'selesai'       => 'success',
    ];

    protected $fillable = [
        'lesson_session_id', 'student_id', 'problem', 'status', 'follow_up',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['student_id', 'problem', 'status', 'follow_up'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('lesson_session');
    }

    public function tapActivity(\Spatie\Activitylog\Models\Activity $activity, string $eventName): void
    {
        // Log tercatat pada parent LessonSession agar muncul di tab Log Aktivitas
        if ($this->lesson_session_id) {
            $activity->subject_type = LessonSession::class;
            $activity->subject_id = $this->lesson_session_id;
        }
        $activity->description = $eventName . ' kasus peserta didik';
    }

    public function lessonSession(): BelongsTo
    {
        return $this->belongsTo(LessonSession::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }
}
