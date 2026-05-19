<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffMember extends Model
{
    protected $fillable = [
        'user_id',
        'name', 'slug', 'nip', 'nuptk', 'gender', 'birth_place', 'birth_date',
        'staff_category_id', 'position', 'is_principal', 'joined_at', 'years_of_service',
        'subjects', 'education', 'certifications', 'experiences',
        'email', 'phone', 'whatsapp', 'social',
        'photo', 'bio', 'quote',
        'order', 'is_active',
        // Kepegawaian
        'employment_status', 'ptk_type', 'sk_cpns', 'sk_cpns_date', 'sk_appointment',
        'appointing_agency', 'rank_grade', 'salary_source', 'civil_servant_start_date', 'nuks',
        // Alamat
        'address', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'postal_code', 'phone_home',
        // Data Pribadi
        'religion', 'nik', 'kk_number', 'mother_name', 'marital_status',
        'spouse_name', 'spouse_nip', 'spouse_occupation', 'nationality', 'npwp', 'taxpayer_name',
        // Dokumen
        'karpeg', 'karis_karsu',
        // Kompetensi
        'has_principal_license', 'has_supervision_training', 'braille_skill', 'sign_language_skill',
        // Bank & GPS
        'bank_name', 'bank_account_number', 'bank_account_name',
        'latitude', 'longitude',
    ];

    protected $casts = [
        'gender' => 'string',
        'birth_date' => 'date',
        'is_principal' => 'boolean',
        'joined_at' => 'date',
        'years_of_service' => 'integer',
        'subjects' => 'array',
        'education' => 'array',
        'certifications' => 'array',
        'experiences' => 'array',
        'social' => 'array',
        'is_active' => 'boolean',
        'sk_cpns_date' => 'date',
        'civil_servant_start_date' => 'date',
        'has_principal_license' => 'boolean',
        'has_supervision_training' => 'boolean',
        'braille_skill' => 'boolean',
        'sign_language_skill' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StaffCategory::class, 'staff_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StaffSchedule::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopePrincipal(Builder $q): Builder
    {
        return $q->where('is_principal', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('order')->orderBy('name');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}
