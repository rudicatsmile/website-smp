<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonSessionCase extends Model
{
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
