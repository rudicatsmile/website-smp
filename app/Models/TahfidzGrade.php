<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfidzGrade extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'surah',
        'score',
        'description',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'teacher_id');
    }
}
