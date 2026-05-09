<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\StaffMember;
use App\Models\StaffSchedule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Jadwal Mengajar & Piket')]
class StaffScheduleIndex extends Component
{
    #[Url(as: 'view')]
    public string $viewMode = 'grid';

    #[Url]
    public string $day = '';

    #[Url]
    public string $type = '';

    #[Url]
    public ?int $staffId = null;

    #[Url(as: 'q')]
    public string $search = '';

    public function setView(string $mode): void
    {
        $this->viewMode = in_array($mode, ['grid', 'list'], true) ? $mode : 'grid';
    }

    public function clearFilters(): void
    {
        $this->reset(['day', 'type', 'staffId', 'search']);
    }

    public function render()
    {
        $query = StaffSchedule::query()
            ->active()
            ->inEffect()
            ->with(['staff', 'subject'])
            ->when($this->day !== '', fn ($q) => $q->where('day_of_week', (int) $this->day))
            ->when($this->type !== '', fn ($q) => $q->where('type', $this->type))
            ->when($this->staffId, fn ($q) => $q->where('staff_member_id', $this->staffId))
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->whereHas('staff', fn ($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('subject', fn ($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                        ->orWhere('class_name', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            });

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        $byDay = $schedules->groupBy('day_of_week');

        $staffOptions = StaffMember::active()->orderBy('name')->get(['id', 'name']);

        return view('livewire.pages.staff-schedule-index', [
            'schedules' => $schedules,
            'byDay' => $byDay,
            'days' => StaffSchedule::DAYS,
            'types' => StaffSchedule::TYPES,
            'typeColors' => StaffSchedule::TYPE_COLORS,
            'staffOptions' => $staffOptions,
        ]);
    }
}
