<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Extracurricular;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EkskulShow extends Component
{
    public Extracurricular $ekskul;

    public function mount(Extracurricular $ekskul): void
    {
        abort_unless($ekskul->is_active, 404);

        $this->ekskul = $ekskul->load([
            'coach',
            'schedules',
            'achievements',
            'galleryItems',
            'members' => fn ($q) => $q->where('status', 'approved')->with('student.schoolClass'),
        ]);
    }

    public function getTitle(): string
    {
        return $this->ekskul->name . ' — Ekstrakurikuler';
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.ekskul-show');
    }
}
