<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpmbDocument extends Model
{
    protected $fillable = ['spmb_registration_id', 'type', 'file_path', 'verified'];

    protected $casts = ['verified' => 'boolean'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(SpmbRegistration::class, 'spmb_registration_id');
    }
}
