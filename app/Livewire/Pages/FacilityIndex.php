<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Facility;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class FacilityIndex extends Component
{
    #[Layout('layouts.app')]
    #[Title('Fasilitas')]
    public function render()
    {
        return view('livewire.pages.facility-index', [
            'facilities' => Facility::active()->get(),
        ]);
    }
}
