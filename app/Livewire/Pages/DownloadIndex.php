<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Download;
use App\Models\DownloadCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DownloadIndex extends Component
{
    use WithPagination;

    #[Url(as: 'kategori')]
    public string $category = '';

    public function updatingCategory(): void { $this->resetPage(); }

    public function track(int $id)
    {
        $download = Download::public()->findOrFail($id);
        $download->increment('download_count');

        return Storage::disk('public')->download($download->file, $download->title);
    }

    #[Layout('layouts.app')]
    #[Title('Download')]
    public function render()
    {
        $downloads = Download::public()->with('category')
            ->when($this->category !== '', fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $this->category)))
            ->latest()
            ->paginate(15);

        return view('livewire.pages.download-index', [
            'downloads' => $downloads,
            'categories' => DownloadCategory::orderBy('name')->get(),
        ]);
    }
}
