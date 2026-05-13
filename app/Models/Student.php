<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    public const GENDERS = [
        'L' => 'Laki-laki',
        'P' => 'Perempuan',
    ];

    protected $fillable = [
        'user_id', 'school_class_id', 'nis', 'nisn', 'name', 'slug',
        'gender', 'birth_date', 'birth_place', 'photo',
        'qr_token', 'qr_token_generated_at',
        'parent_name', 'parent_phone', 'parent_email',
        'mother_name', 'mother_phone',
        'address', 'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'qr_token_generated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_student')
            ->withPivot(['relation', 'is_primary'])
            ->withTimestamps();
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function violations(): HasMany
    {
        return $this->hasMany(StudentViolation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function tahfidzParticipant(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TahfidzParticipant::class);
    }

    public function tahfidzGrades(): HasMany
    {
        return $this->hasMany(TahfidzGrade::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    public function generateQrToken(bool $force = false): string
    {
        if (! $force && $this->qr_token) {
            return $this->qr_token;
        }
        do {
            $token = strtoupper(\Illuminate\Support\Str::random(16));
        } while (static::where('qr_token', $token)->where('id', '!=', $this->id)->exists());

        $this->forceFill([
            'qr_token' => $token,
            'qr_token_generated_at' => now(),
        ])->save();

        return $token;
    }

    public static function findByQrToken(string $token): ?self
    {
        return static::where('qr_token', trim($token))->first();
    }
}
