<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Achievement;
use App\Models\Facility;
use App\Models\News;
use App\Models\Program;
use App\Models\Slider;
use App\Models\SpmbPeriod;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Home extends Component
{
    #[Layout('layouts.app')]
    #[Title('Beranda')]
    public function render()
    {
        return view('livewire.pages.home', [
            'sliders' => Slider::active()->get(),
            'facilities' => Facility::active()->limit(6)->get(),
            'programs' => Program::active()->featured()->ordered()->limit(6)->get(),
            'achievements' => Achievement::active()->featured()->orderBy('order')->latest('achieved_at')->limit(4)->get(),
            'latestNews' => News::published()->with('category')->latest('published_at')->limit(3)->get(),
            'spmb' => SpmbPeriod::active(),
        ]);
    }
}
