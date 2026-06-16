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
        // Kependudukan
        'nik', 'religion', 'kk_number', 'birth_certificate_number', 'skhun',
        // Alamat
        'address', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'postal_code',
        'living_with', 'transportation', 'phone',
        // Orang Tua Ayah
        'parent_name', 'parent_phone', 'parent_email',
        'father_birth_year', 'father_education', 'father_occupation', 'father_income', 'father_nik',
        // Orang Tua Ibu
        'mother_name', 'mother_phone',
        'mother_birth_year', 'mother_education', 'mother_occupation', 'mother_income', 'mother_nik',
        // Wali
        'guardian_name', 'guardian_birth_year', 'guardian_education', 'guardian_occupation', 'guardian_income', 'guardian_nik',
        // Sekolah & Dokumen
        'previous_school', 'child_order', 'un_number', 'certificate_number',
        // Bantuan Sosial
        'kps_recipient', 'kps_number', 'kip_recipient', 'kip_number', 'kip_name', 'kks_number', 'pip_eligible', 'pip_reason',
        // Fisik & Lokasi
        'weight', 'height', 'head_circumference', 'siblings_count', 'home_distance', 'special_needs', 'latitude', 'longitude',
        // Bank
        'bank_name', 'bank_account_number', 'bank_account_name',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'qr_token_generated_at' => 'datetime',
        'is_active' => 'boolean',
        'kps_recipient' => 'boolean',
        'kip_recipient' => 'boolean',
        'pip_eligible' => 'boolean',
        'father_birth_year' => 'integer',
        'mother_birth_year' => 'integer',
        'guardian_birth_year' => 'integer',
        'child_order' => 'integer',
        'weight' => 'integer',
        'height' => 'integer',
        'head_circumference' => 'integer',
        'siblings_count' => 'integer',
        'home_distance' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
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

    public function assessmentScores(): HasMany
    {
        return $this->hasMany(SessionAssessmentScore::class);
    }

    public function examScores(): HasMany
    {
        return $this->hasMany(ExamScore::class);
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

    public function extracurricularMemberships(): HasMany
    {
        return $this->hasMany(ExtracurricularMember::class);
    }

    public function elections(): BelongsToMany
    {
        return $this->belongsToMany(Election::class, 'election_voters')
            ->withPivot(['token', 'has_voted', 'voted_at'])
            ->withTimestamps();
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
