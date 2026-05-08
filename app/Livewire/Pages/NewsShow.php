<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\News;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class NewsShow extends Component
{
    public News $news;

    public function mount(string $slug): void
    {
        $this->news = News::published()->where('slug', $slug)->firstOrFail();
        $this->news->increment('views');
        $this->news->load(['category', 'author', 'tags']);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.news-show', [
            'related' => News::published()
                ->where('id', '!=', $this->news->id)
                ->where('category_id', $this->news->category_id)
                ->latest('published_at')
                ->limit(3)
                ->get(),
            'pageTitle' => $this->news->title,
        ])->title($this->news->title);
    }
}
