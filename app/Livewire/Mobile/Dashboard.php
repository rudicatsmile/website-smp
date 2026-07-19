<?php

namespace App\Livewire\Mobile;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.mobile')]
#[Title('Beranda - Mobile')]
class Dashboard extends Component
{
    public $user;
    public $todaySessionsCount = 0;
    public $pendingSessionsCount = 0;

    public function mount()
    {
        $this->user = Auth::user();

        if ($this->user && $this->user->staffMember) {
            $today = now()->format('Y-m-d');
            
            $this->todaySessionsCount = \App\Models\LessonSession::where('staff_member_id', $this->user->staffMember->id)
                ->where('session_date', $today)
                ->count();
                
            $this->pendingSessionsCount = \App\Models\LessonSession::where('staff_member_id', $this->user->staffMember->id)
                ->whereIn('status', ['draft', 'published', 'ongoing', 'incomplete'])
                ->count();
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('mobile.login');
    }

    public function render()
    {
        return view('livewire.mobile.dashboard');
    }
}
