<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Achievement;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AchievementShow extends Component
{
    public Achievement $achievement;

    public function mount(string $slug): void
    {
        $this->achievement = Achievement::active()->where('slug', $slug)->firstOrFail();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $related = Achievement::active()
            ->where('id', '!=', $this->achievement->id)
            ->orderBy('order')
            ->latest('achieved_at')
            ->limit(3)
            ->get();

        return view('livewire.pages.achievement-show', [
            'related' => $related,
        ])->title($this->achievement->title.' — Prestasi');
    }
}
