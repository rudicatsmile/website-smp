<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Extracurricular;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class EkskulIndex extends Component
{
    #[Url(as: 'kategori')]
    public string $category = '';

    #[Layout('layouts.app')]
    #[Title('Ekstrakurikuler')]
    public function render()
    {
        $ekskuls = Extracurricular::active()
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->withCount(['members' => fn ($q) => $q->where('status', 'approved')])
            ->ordered()
            ->get();

        return view('livewire.pages.ekskul-index', compact('ekskuls'));
    }
}
