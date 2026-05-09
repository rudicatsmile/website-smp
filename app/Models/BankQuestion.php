<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankQuestion extends Model
{
    protected $fillable = [
        'question_bank_id', 'type', 'body', 'explanation', 'score', 'order',
    ];

    protected $casts = [
        'score' => 'integer',
        'order' => 'integer',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(BankQuestionOption::class)->orderBy('order');
    }
}
