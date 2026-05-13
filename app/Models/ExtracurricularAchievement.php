<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularAchievement extends Model
{
    protected $fillable = [
        'extracurricular_id', 'title', 'level', 'rank', 'achieved_at', 'description', 'cover',
    ];

    protected $casts = [
        'achieved_at' => 'date',
    ];

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover ? asset('storage/' . $this->cover) : null;
    }

    public function extracurricular(): BelongsTo
    {
        return $this->belongsTo(Extracurricular::class);
    }
}
