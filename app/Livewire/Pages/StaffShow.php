<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\StaffMember;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Profil Guru & Staf')]
class StaffShow extends Component
{
    public StaffMember $member;

    public string $activeTab = 'bio';

    public function mount(string $slug): void
    {
        $this->member = StaffMember::active()
            ->with(['schedules' => fn ($q) => $q->where('is_active', true), 'schedules.subject'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $relatedStaff = StaffMember::active()
            ->where('id', '!=', $this->member->id)
            ->when($this->member->staff_category_id, function ($q) {
                $q->where('staff_category_id', $this->member->staff_category_id);
            })
            ->ordered()
            ->take(4)
            ->get();

        return view('livewire.pages.staff-show', compact('relatedStaff'));
    }
}
