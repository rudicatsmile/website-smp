<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Material;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MaterialShow extends Component
{
    public Material $material;

    public function mount(string $slug): void
    {
        $this->material = Material::query()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->active()
            ->public()
            ->published()
            ->firstOrFail();

        $this->material->increment('view_count');
    }

    public function getTitle(): string
    {
        return $this->material->title;
    }

    public function render()
    {
        $related = Material::query()
            ->with(['category', 'author'])
            ->active()
            ->public()
            ->published()
            ->where('material_category_id', $this->material->material_category_id)
            ->where('id', '!=', $this->material->id)
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('livewire.pages.material-show', [
            'material' => $this->material,
            'related' => $related,
        ])->title($this->material->title);
    }
}
