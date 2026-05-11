<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentNoteMessage extends Model
{
    protected $fillable = [
        'parent_note_id', 'sender_type',
        'user_id', 'staff_member_id',
        'body', 'attachments', 'is_internal', 'read_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function note(): BelongsTo
    {
        return $this->belongsTo(ParentNote::class, 'parent_note_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staffMember(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class);
    }

    public function scopePublic(Builder $q): Builder
    {
        return $q->where('is_internal', false);
    }
}
