<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonSessionMaterial extends Model
{
    protected $fillable = ['lesson_session_id', 'class_material_id', 'order'];

    protected $casts = [
        'order' => 'integer',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(LessonSession::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(ClassMaterial::class, 'class_material_id');
    }
}
