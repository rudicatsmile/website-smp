<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    protected $fillable = ['session_id', 'user_message', 'bot_response', 'matched_faq_id', 'was_helpful'];

    protected function casts(): array
    {
        return [
            'was_helpful' => 'boolean',
        ];
    }
}
