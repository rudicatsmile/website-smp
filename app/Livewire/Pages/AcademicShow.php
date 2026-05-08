<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Academic;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AcademicShow extends Component
{
    public Academic $academic;

    public function mount(string $slug): void
    {
        $this->academic = Academic::active()->where('slug', $slug)->firstOrFail();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.academic-show')->title($this->academic->name);
    }
}
