<?php

namespace App\Livewire\Pages;

use App\Models\Program;
use Livewire\Component;
use Livewire\WithPagination;

class ProgramIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $programs = Program::active()
            ->ordered()
            ->paginate(12);

        return view('livewire.pages.program-index', [
            'programs' => $programs,
        ])
            ->layout('layouts.app')
            ->title('Program Unggulan');
    }
}
