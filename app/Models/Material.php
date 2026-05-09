<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    protected $fillable = [
        'material_category_id', 'staff_member_id',
        'title', 'slug', 'excerpt', 'description',
        'type', 'grade', 'phase', 'curriculum', 'semester', 'academic_year',
        'file_path', 'file_name', 'file_size', 'file_mime', 'cover_image',
        'tags',
        'is_public', 'is_featured', 'is_active',
        'download_count', 'view_count',
        'published_at', 'order',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'download_count' => 'integer',
            'view_count' => 'integer',
            'file_size' => 'integer',
            'order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null;
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes <= 0) {
            return '-';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = (int) floor(log($bytes, 1024));
        $i = min($i, count($units) - 1);

        return round($bytes / (1024 ** $i), 2) . ' ' . $units[$i];
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'modul_ajar' => 'Modul Ajar',
            'rpp' => 'RPP',
            'lkpd' => 'LKPD',
            'bahan_ajar' => 'Bahan Ajar',
            'atp' => 'ATP',
            'cp' => 'Capaian Pembelajaran',
            'silabus' => 'Silabus',
            default => 'Lainnya',
        };
    }

    public function getGradeLabelAttribute(): string
    {
        return match ($this->grade) {
            '7', '8', '9' => 'Kelas ' . $this->grade,
            'umum' => 'Umum',
            default => (string) $this->grade,
        };
    }

    public function getCurriculumLabelAttribute(): string
    {
        return match ($this->curriculum) {
            'merdeka' => 'Kurikulum Merdeka',
            'k13' => 'Kurikulum 2013',
            default => 'Lainnya',
        };
    }
}
