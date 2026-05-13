<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahfidzParticipant extends Model
{
    protected $fillable = [
        'student_id',
        'surah_target',
        'is_active',
        'enrolled_at',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'enrolled_at' => 'date',
        'surah_target' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(TahfidzGrade::class, 'student_id', 'student_id');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function getSurahSelesaiAttribute(): int
    {
        return $this->grades()->count();
    }

    public function getProgresPresentAttribute(): float
    {
        if ($this->surah_target <= 0) {
            return 0;
        }
        return round(($this->surah_selesai / $this->surah_target) * 100, 1);
    }

    public function getNilaiRataRataAttribute(): float
    {
        return round((float) $this->grades()->avg('score'), 1);
    }
}
