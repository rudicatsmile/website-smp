<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularSchedule extends Model
{
    protected $fillable = [
        'extracurricular_id', 'day_of_week', 'start_time', 'end_time', 'location', 'notes',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    private const DAY_NAMES = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
        4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
    ];

    public function getDayNameAttribute(): string
    {
        return self::DAY_NAMES[$this->day_of_week] ?? '—';
    }

    public function extracurricular(): BelongsTo
    {
        return $this->belongsTo(Extracurricular::class);
    }
}
