<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselingMessage extends Model
{
    protected $fillable = [
        'counseling_ticket_id', 'sender_type',
        'user_id', 'staff_member_id',
        'body', 'attachments', 'is_internal',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(CounselingTicket::class, 'counseling_ticket_id');
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
