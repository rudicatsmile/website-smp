<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamScore extends Model
{
    protected $fillable = [
        'exam_session_id', 'student_id', 'score', 'is_remedial', 'notes',
    ];

    protected $casts = [
        'score'       => 'decimal:2',
        'is_remedial' => 'boolean',
    ];

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
