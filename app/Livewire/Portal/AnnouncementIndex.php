<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\ClassAnnouncement;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
#[Title('Pengumuman Kelas')]
class AnnouncementIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $items = ClassAnnouncement::query()
            ->active()
            ->where(function ($q) use ($student) {
                $q->whereNull('school_class_id')->orWhere('school_class_id', $student->school_class_id);
            })
            ->with(['teacher', 'schoolClass'])
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('livewire.portal.announcement-index', compact('items'));
    }
}
