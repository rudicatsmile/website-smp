<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpmbPeriod extends Model
{
    protected $fillable = [
        'name', 'start_date', 'end_date', 'quota', 'fee', 'description', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'quota' => 'integer',
            'fee' => 'integer',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(SpmbRegistration::class);
    }

    public static function active(): ?self
    {
        return static::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest('id')
            ->first();
    }
}
