<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracerStudy extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'graduation_year',
        'current_status', 'company_or_institution', 'position',
        'city', 'income_range', 'school_relevance', 'school_quality',
        'suggestions', 'allow_publish', 'is_processed', 'processed_at', 'processed_by',
    ];

    protected $casts = [
        'graduation_year'   => 'integer',
        'school_relevance'  => 'integer',
        'school_quality'    => 'integer',
        'allow_publish'     => 'boolean',
        'is_processed'      => 'boolean',
        'processed_at'      => 'datetime',
    ];

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getCurrentStatusLabelAttribute(): string
    {
        return match ($this->current_status) {
            'working'      => 'Bekerja',
            'studying'     => 'Kuliah',
            'entrepreneur' => 'Wirausaha',
            'both'         => 'Kuliah & Bekerja',
            'unemployed'   => 'Belum Bekerja',
            default        => 'Lainnya',
        };
    }

    public function getIncomeLabelAttribute(): string
    {
        return match ($this->income_range) {
            '<2jt'             => '< Rp 2 juta',
            '2-5jt'            => 'Rp 2 – 5 juta',
            '5-10jt'           => 'Rp 5 – 10 juta',
            '10-20jt'          => 'Rp 10 – 20 juta',
            '>20jt'            => '> Rp 20 juta',
            'prefer_not_to_say'=> 'Tidak ingin menyebutkan',
            default            => '—',
        };
    }

    public function scopeUnprocessed(Builder $q): Builder
    {
        return $q->where('is_processed', false);
    }
}
