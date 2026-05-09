<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\ClassAnnouncement;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Detail Pengumuman')]
class AnnouncementShow extends Component
{
    public ClassAnnouncement $announcement;

    public function mount(string $slug): void
    {
        $this->announcement = ClassAnnouncement::where('slug', $slug)->firstOrFail();
        $student = auth()->user()->student;
        $classId = $this->announcement->school_class_id;
        abort_unless($classId === null || ($student && $student->school_class_id === $classId), 403);
        $this->announcement->load(['teacher', 'schoolClass']);
    }

    public function render()
    {
        return view('livewire.portal.announcement-show');
    }
}
