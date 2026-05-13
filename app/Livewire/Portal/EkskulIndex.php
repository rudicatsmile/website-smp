<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Extracurricular;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Ekstrakurikuler Saya')]
class EkskulIndex extends Component
{
    public function render()
    {
        $user    = auth()->user();
        $student = $user->student;

        $myMemberships = $student
            ? $student->extracurricularMemberships()
                ->with('extracurricular')
                ->latest()
                ->get()
            : collect();

        $myIds = $myMemberships->pluck('extracurricular_id')->toArray();

        $available = Extracurricular::active()
            ->whereNotIn('id', $myIds)
            ->withCount(['members' => fn ($q) => $q->where('status', 'approved')])
            ->ordered()
            ->get();

        return view('livewire.portal.ekskul-index', [
            'myMemberships' => $myMemberships,
            'available'     => $available,
            'isStudent'     => $student !== null,
        ]);
    }
}
