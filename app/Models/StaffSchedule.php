<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffSchedule extends Model
{
    public const DAYS = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        0 => 'Minggu',
    ];

    public const TYPES = [
        'mengajar' => 'Mengajar',
        'piket' => 'Piket',
        'rapat' => 'Rapat Rutin',
        'lainnya' => 'Lainnya',
    ];

    public const TYPE_COLORS = [
        'mengajar' => 'emerald',
        'piket' => 'amber',
        'rapat' => 'sky',
        'lainnya' => 'slate',
    ];

    protected $fillable = [
        'staff_member_id', 'material_category_id',
        'type', 'day_of_week', 'start_time', 'end_time',
        'period', 'class_name', 'location', 'notes', 'color',
        'effective_from', 'effective_until', 'academic_year', 'semester',
        'is_active', 'order',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_active' => 'boolean',
            'order' => 'integer',
            'effective_from' => 'date',
            'effective_until' => 'date',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, int $day)
    {
        return $query->where('day_of_week', $day);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInEffect($query)
    {
        $today = now()->toDateString();

        return $query->where(function ($q) use ($today) {
            $q->whereNull('effective_from')->orWhere('effective_from', '<=', $today);
        })->where(function ($q) use ($today) {
            $q->whereNull('effective_until')->orWhere('effective_until', '>=', $today);
        });
    }

    public function getDayLabelAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? '-';
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getTimeRangeAttribute(): string
    {
        $start = $this->start_time ? substr((string) $this->start_time, 0, 5) : '';
        $end = $this->end_time ? substr((string) $this->end_time, 0, 5) : '';

        return trim($start . ' – ' . $end, ' –');
    }

    public function getDisplayTitleAttribute(): string
    {
        if ($this->type === 'mengajar') {
            $parts = array_filter([$this->subject?->name, $this->class_name ? 'Kelas ' . $this->class_name : null]);

            return $parts ? implode(' • ', $parts) : 'Mengajar';
        }

        return $this->type_label;
    }
}
