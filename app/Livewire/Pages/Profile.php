<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Settings\ProfileSettings;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Profile extends Component
{
    #[Layout('layouts.app')]
    #[Title('Profil Sekolah')]
    public function render()
    {
        return view('livewire.pages.profile', [
            'profile' => app(ProfileSettings::class),
        ]);
    }
}
