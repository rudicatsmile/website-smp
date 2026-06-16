<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectionCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'candidate_number',
        'name',
        'photo',
        'tagline',
        'vision',
        'mission',
        'experience',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ElectionVote::class);
    }
}
