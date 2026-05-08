<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Academic;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class AcademicIndex extends Component
{
    #[Layout('layouts.app')]
    #[Title('Akademik')]
    public function render()
    {
        return view('livewire.pages.academic-index', [
            'academics' => Academic::active()->get(),
        ]);
    }
}
