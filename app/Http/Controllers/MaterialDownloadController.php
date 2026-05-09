<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialDownloadController extends Controller
{
    public function __invoke(string $slug): StreamedResponse
    {
        $material = Material::query()
            ->where('slug', $slug)
            ->active()
            ->public()
            ->published()
            ->firstOrFail();

        abort_unless($material->file_path && Storage::disk('public')->exists($material->file_path), 404);

        $material->increment('download_count');

        return Storage::disk('public')->download(
            $material->file_path,
            $material->file_name ?: basename($material->file_path),
        );
    }
}
