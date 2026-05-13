<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Alumni;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class AlumniIndex extends Component
{
    #[Url(as: 'tahun')]
    public ?int $year = null;

    #[Url(as: 'status')]
    public ?string $status = null;

    #[Layout('layouts.app')]
    #[Title('Profil Alumni')]
    public function render()
    {
        $query = Alumni::published()->orderedDefault();

        if ($this->year) {
            $query->where('graduation_year', $this->year);
        }

        if ($this->status) {
            $query->where('current_status', $this->status);
        }

        $alumni = $query->get();

        $years = Alumni::published()
            ->distinct()
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year');

        return view('livewire.pages.alumni-index', compact('alumni', 'years'));
    }

    public function setYear(?int $year): void
    {
        $this->year = ($this->year === $year) ? null : $year;
    }

    public function setStatus(?string $status): void
    {
        $this->status = ($this->status === $status) ? null : $status;
    }
}
