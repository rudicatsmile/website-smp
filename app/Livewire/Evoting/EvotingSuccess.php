<?php

namespace App\Livewire\Evoting;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class EvotingSuccess extends Component
{
    public function render()
    {
        return view('livewire.evoting.evoting-success');
    }
}
