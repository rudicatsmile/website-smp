<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'category',
        'is_holiday',
        'is_active',
        'color',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_holiday' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function scopeHoliday($query)
    {
        return $query->where('is_holiday', true);
    }
}
