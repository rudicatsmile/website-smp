<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentViolation extends Model
{
    public const CATEGORIES = [
        'kedisiplinan' => 'Kedisiplinan',
        'atribut' => 'Atribut',
        'akademik' => 'Akademik',
        'lainnya' => 'Lainnya',
    ];

    protected $fillable = [
        'student_id', 'staff_member_id', 'date',
        'category', 'description', 'points', 'action_taken',
    ];

    protected $casts = [
        'date' => 'date',
        'points' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }
}
