<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\ClassMaterial;
use App\Models\MaterialCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
#[Title('Materi Kelas')]
class MaterialIndex extends Component
{
    use WithPagination;

    #[Url]
    public ?int $subjectId = null;

    public function updatedSubjectId(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $items = ClassMaterial::query()
            ->published()
            ->where(function ($q) use ($student) {
                $q->whereNull('school_class_id')->orWhere('school_class_id', $student->school_class_id);
            })
            ->when($this->subjectId, fn ($q) => $q->where('material_category_id', $this->subjectId))
            ->with(['subject', 'teacher', 'schoolClass'])
            ->latest()
            ->paginate(12);

        $subjects = MaterialCategory::orderBy('name')->get();

        return view('livewire.portal.material-index', compact('items', 'subjects'));
    }
}
