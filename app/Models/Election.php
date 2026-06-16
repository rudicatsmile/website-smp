<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(ElectionCandidate::class);
    }

    public function voters(): HasMany
    {
        return $this->hasMany(ElectionVoter::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ElectionVote::class);
    }
}
