<?php

namespace App\Livewire\Mobile\TeachingSessions;

use App\Models\LessonSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.mobile')]
#[Title('Sesi Mengajar - Mobile')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();
        
        $sessions = collect();
        if ($user && $user->staffMember) {
            $sessions = LessonSession::with(['schoolClass', 'subject'])
                ->where('staff_member_id', $user->staffMember->id)
                ->orderBy('session_date', 'desc')
                ->orderBy('start_time', 'desc')
                ->paginate(10);
        }

        return view('livewire.mobile.teaching-sessions.index', [
            'sessions' => $sessions
        ]);
    }
}
