<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\News;
use App\Models\NewsCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $category = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingCategory(): void { $this->resetPage(); }

    #[Layout('layouts.app')]
    #[Title('Berita')]
    public function render()
    {
        $news = News::published()
            ->with('category')
            ->when($this->search !== '', fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->category !== '', fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $this->category)))
            ->latest('published_at')
            ->paginate(9);

        return view('livewire.pages.news-index', [
            'news' => $news,
            'categories' => NewsCategory::orderBy('name')->get(),
        ]);
    }
}
