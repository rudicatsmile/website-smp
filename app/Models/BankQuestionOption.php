<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankQuestionOption extends Model
{
    protected $fillable = [
        'bank_question_id', 'label', 'is_correct', 'order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'order' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(BankQuestion::class, 'bank_question_id');
    }
}
