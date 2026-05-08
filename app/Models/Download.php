<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'file', 'description', 'download_count', 'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'download_count' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DownloadCategory::class, 'category_id');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
