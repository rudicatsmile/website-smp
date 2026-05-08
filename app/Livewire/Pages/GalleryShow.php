<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Gallery;
use Livewire\Attributes\Layout;
use Livewire\Component;

class GalleryShow extends Component
{
    public Gallery $gallery;

    public function mount(string $slug): void
    {
        $this->gallery = Gallery::active()->where('slug', $slug)->with('items')->firstOrFail();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.gallery-show')->title($this->gallery->title);
    }
}
