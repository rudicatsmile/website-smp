<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionAssessment extends Model
{
    public const TYPES = [
        'kuis'          => 'Kuis',
        'ulangan_harian'=> 'Ulangan Harian',
        'tugas_kelas'   => 'Tugas Kelas',
    ];

    public const TYPE_COLORS = [
        'kuis'          => 'info',
        'ulangan_harian'=> 'warning',
        'tugas_kelas'   => 'success',
    ];

    protected $fillable = [
        'lesson_session_id', 'title', 'type', 'max_score', 'notes',
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
    ];

    public function lessonSession(): BelongsTo
    {
        return $this->belongsTo(LessonSession::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(SessionAssessmentScore::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst((string) $this->type);
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPE_COLORS[$this->type] ?? 'gray';
    }

    public function getScoresCountAttribute(): int
    {
        return $this->scores()->whereNotNull('score')->count();
    }

    public function getAverageScoreAttribute(): ?float
    {
        $avg = $this->scores()->whereNotNull('score')->avg('score');
        return $avg !== null ? round((float) $avg, 2) : null;
    }
}
