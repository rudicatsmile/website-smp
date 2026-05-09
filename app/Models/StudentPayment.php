<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPayment extends Model
{
    public const TYPES = [
        'spp' => 'SPP',
        'seragam' => 'Seragam',
        'kegiatan' => 'Kegiatan',
        'lainnya' => 'Lainnya',
    ];

    public const STATUSES = [
        'unpaid' => 'Belum Dibayar',
        'paid' => 'Lunas',
        'overdue' => 'Jatuh Tempo',
    ];

    public const STATUS_COLORS = [
        'unpaid' => 'amber',
        'paid' => 'emerald',
        'overdue' => 'rose',
    ];

    protected $fillable = [
        'student_id', 'type', 'period', 'amount',
        'due_date', 'status', 'paid_at', 'paid_amount', 'note',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'integer',
        'paid_amount' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeUnpaid(Builder $q): Builder
    {
        return $q->whereIn('status', ['unpaid', 'overdue']);
    }

    public function scopePaid(Builder $q): Builder
    {
        return $q->where('status', 'paid');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function getAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->amount, 0, ',', '.');
    }
}
