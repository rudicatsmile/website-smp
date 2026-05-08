<?php

namespace App\Livewire\Pages;

use App\Models\Program;
use Livewire\Component;

class ProgramShow extends Component
{
    public Program $program;

    public function mount($slug)
    {
        $this->program = Program::where('slug', $slug)
            ->active()
            ->firstOrFail();
    }

    public function render()
    {
        $relatedPrograms = Program::active()
            ->where('id', '!=', $this->program->id)
            ->ordered()
            ->take(4)
            ->get();

        return view('livewire.pages.program-show', [
            'program' => $this->program,
            'relatedPrograms' => $relatedPrograms,
        ])
            ->layout('layouts.app')
            ->title($this->program->title);
    }
}
