<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\SpmbPeriod;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SpmbLanding extends Component
{
    #[Layout('layouts.app')]
    #[Title('SPMB - Sistem Penerimaan Murid Baru')]
    public function render()
    {
        return view('livewire.pages.spmb-landing', [
            'period' => SpmbPeriod::active(),
        ]);
    }
}
