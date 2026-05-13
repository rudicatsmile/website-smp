<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Alumni;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AlumniShow extends Component
{
    public Alumni $alumni;

    public function mount(Alumni $alumni): void
    {
        abort_unless($alumni->is_published, 404);
        $this->alumni = $alumni;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.alumni-show')
            ->title($this->alumni->name . ' — Alumni');
    }
}
