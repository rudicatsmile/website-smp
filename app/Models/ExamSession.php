<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSession extends Model
{
    public const TYPES = [
        'uts'      => 'UTS',
        'uas'      => 'UAS',
        'pts'      => 'PTS',
        'pas'      => 'PAS',
        'remedial' => 'Remedial',
    ];

    public const TYPE_COLORS = [
        'uts'      => 'warning',
        'uas'      => 'danger',
        'pts'      => 'info',
        'pas'      => 'purple',
        'remedial' => 'gray',
    ];

    protected $fillable = [
        'school_class_id', 'material_category_id', 'staff_member_id',
        'exam_type', 'title', 'exam_date',
        'academic_year', 'semester', 'max_score', 'notes',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'max_score' => 'decimal:2',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(ExamScore::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->exam_type] ?? strtoupper((string) $this->exam_type);
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPE_COLORS[$this->exam_type] ?? 'gray';
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
