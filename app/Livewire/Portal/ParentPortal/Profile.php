<?php

namespace App\Livewire\Portal\ParentPortal;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Profil Orang Tua')]
class Profile extends Component
{
    public function render()
    {
        $user = auth()->user();
        return view('livewire.portal.parent-portal.profile', compact('user'));
    }
}
