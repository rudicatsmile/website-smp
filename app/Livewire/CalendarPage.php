<?php

namespace App\Livewire;

use App\Models\SchoolEvent;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kalender Agenda')]
class CalendarPage extends Component
{
    public $currentMonth;
    public $currentYear;
    public $selectedDate = null;
    public $events = [];
    public $categoryFilter = 'all';

    public function mount()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->loadEvents();
    }

    public function updatedCategoryFilter()
    {
        $this->loadEvents();
    }

    public function goToToday()
    {
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $query = SchoolEvent::active()
            ->whereYear('event_date', $this->currentYear)
            ->whereMonth('event_date', $this->currentMonth);

        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        $this->events = $query->orderBy('event_date')->get();
    }

    public function getMonthStatsProperty()
    {
        return [
            'total' => $this->events->count(),
            'akademik' => $this->events->where('category', 'akademik')->count(),
            'ekstrakurikuler' => $this->events->where('category', 'ekstrakurikuler')->count(),
            'libur' => $this->events->where('is_holiday', true)->count(),
        ];
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->selectedDate = null;
        $this->loadEvents();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->selectedDate = null;
        $this->loadEvents();
    }

    public function selectDate($day)
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, $day)->format('Y-m-d');
        $this->selectedDate = $date;
    }

    public function getCalendarDaysProperty()
    {
        $days = [];
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $lastDay = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth();

        // Add empty cells for days before the first day of the month
        for ($i = 0; $i < $firstDay->dayOfWeek; $i++) {
            $days[] = ['day' => null, 'isCurrentMonth' => false];
        }

        // Add days of the month
        for ($day = 1; $day <= $lastDay->day; $day++) {
            $dateStr = Carbon::create($this->currentYear, $this->currentMonth, $day)->format('Y-m-d');
            $hasEvents = $this->events->contains('event_date', $dateStr);
            $isToday = $dateStr === Carbon::now()->format('Y-m-d');

            $days[] = [
                'day' => $day,
                'isCurrentMonth' => true,
                'hasEvents' => $hasEvents,
                'isToday' => $isToday,
                'dateStr' => $dateStr,
            ];
        }

        return $days;
    }

    public function getMonthNameProperty()
    {
        return Carbon::create($this->currentYear, $this->currentMonth, 1)->translatedFormat('F Y');
    }

    public function getSelectedDateEventsProperty()
    {
        if (!$this->selectedDate) {
            return collect();
        }

        return SchoolEvent::active()
            ->where('event_date', $this->selectedDate)
            ->orderBy('start_time')
            ->get();
    }

    public function getUpcomingEventsProperty()
    {
        return SchoolEvent::active()
            ->upcoming()
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.calendar-page');
    }
}
