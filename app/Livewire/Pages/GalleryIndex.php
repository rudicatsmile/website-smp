<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Gallery;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryIndex extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Galeri')]
    public function render()
    {
        return view('livewire.pages.gallery-index', [
            'galleries' => Gallery::active()->withCount('items')->latest('published_at')->paginate(12),
        ]);
    }
}
