<?php

namespace App\Livewire\Mobile;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.mobile')]
#[Title('Profil Saya')]
class Profile extends Component
{
    public function render()
    {
        $user = auth()->user();
        return view('livewire.mobile.profile', compact('user'));
    }
}
