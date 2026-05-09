<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\CounselingTicket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
#[Title('Konseling BK')]
class CounselingIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $tickets = CounselingTicket::query()
            ->where('user_id', $user->id)
            ->withCount('messages')
            ->orderByDesc('last_activity_at')
            ->paginate(10);

        return view('livewire.portal.counseling-index', compact('tickets'));
    }
}
