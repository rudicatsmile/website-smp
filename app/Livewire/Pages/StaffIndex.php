<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\StaffCategory;
use App\Models\StaffMember;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Direktori Guru & Staf')]
class StaffIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $categoryId = null;

    public function render()
    {
        $categories = StaffCategory::active()->ordered()->get();

        $query = StaffMember::active()
            ->with('category')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('position', 'like', '%' . $this->search . '%')
                    ->orWhere('subjects', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryId, function ($q) {
                $q->where('staff_category_id', $this->categoryId);
            });

        $principal = StaffMember::active()->principal()->first();

        $staff = $query->ordered()->paginate(12);

        return view('livewire.pages.staff-index', compact('categories', 'staff', 'principal'));
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }
}
