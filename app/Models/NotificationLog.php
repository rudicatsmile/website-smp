<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationLog extends Model
{
    public const CHANNELS = [
        'whatsapp' => 'WhatsApp',
        'email'    => 'Email',
    ];

    public const EVENTS = [
        'absensi'      => 'Absensi',
        'payment_due'  => 'Tagihan',
        'announcement' => 'Pengumuman',
        'rapor'        => 'Rapor',
        'manual'       => 'Manual',
    ];

    public const STATUSES = [
        'pending' => 'Menunggu',
        'sent'    => 'Terkirim',
        'failed'  => 'Gagal',
    ];

    protected $fillable = [
        'notifiable_type', 'notifiable_id',
        'recipient_name', 'recipient_phone', 'recipient_email',
        'channel', 'event', 'subject', 'message',
        'status', 'error', 'payload', 'sent_at', 'triggered_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function triggerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function scopeFailed(Builder $q): Builder
    {
        return $q->where('status', 'failed');
    }

    public function scopeSent(Builder $q): Builder
    {
        return $q->where('status', 'sent');
    }

    public function getChannelLabelAttribute(): string
    {
        return self::CHANNELS[$this->channel] ?? ucfirst((string) $this->channel);
    }

    public function getEventLabelAttribute(): string
    {
        return self::EVENTS[$this->event] ?? ucfirst((string) $this->event);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }
}
