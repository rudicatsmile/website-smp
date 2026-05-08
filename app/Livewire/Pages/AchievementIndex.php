<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Achievement;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class AchievementIndex extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    #[Title('Prestasi Murid')]
    public function render()
    {
        $achievements = Achievement::active()
            ->orderBy('order')
            ->latest('achieved_at')
            ->latest('id')
            ->paginate(12);

        return view('livewire.pages.achievement-index', compact('achievements'));
    }
}
