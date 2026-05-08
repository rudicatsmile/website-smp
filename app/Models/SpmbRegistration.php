<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SpmbRegistration extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'spmb_period_id', 'registration_number',
        'full_name', 'nick_name', 'gender', 'birth_place', 'birth_date',
        'nik', 'nisn', 'religion', 'address', 'phone', 'email',
        'father_name', 'father_job', 'father_phone',
        'mother_name', 'mother_job', 'mother_phone', 'guardian_name',
        'previous_school', 'graduation_year', 'npsn',
        'status', 'admin_note',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'admin_note'])
            ->logOnlyDirty();
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(SpmbPeriod::class, 'spmb_period_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(SpmbDocument::class);
    }

    public static function generateNumber(): string
    {
        $prefix = 'SPMB-' . now()->format('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;

        return $prefix . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }
}
