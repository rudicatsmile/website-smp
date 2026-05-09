<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Material;
use App\Models\MaterialCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Bank Materi & Modul Ajar')]
class MaterialIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public ?int $categoryId = null;

    #[Url]
    public string $type = '';

    #[Url]
    public string $grade = '';

    #[Url]
    public string $curriculum = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedGrade(): void
    {
        $this->resetPage();
    }

    public function updatedCurriculum(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'categoryId', 'type', 'grade', 'curriculum']);
        $this->resetPage();
    }

    public function render()
    {
        $categories = MaterialCategory::active()->ordered()->get();

        $query = Material::query()
            ->with(['category', 'author'])
            ->active()
            ->public()
            ->published()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                        ->orWhere('tags', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryId, fn ($q) => $q->where('material_category_id', $this->categoryId))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->grade, fn ($q) => $q->where('grade', $this->grade))
            ->when($this->curriculum, fn ($q) => $q->where('curriculum', $this->curriculum));

        $featured = Material::active()->public()->published()->featured()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        $materials = $query->orderBy('order')->latest('published_at')->paginate(12);

        return view('livewire.pages.material-index', compact('categories', 'materials', 'featured'));
    }
}
