<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\Student;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Absensi')]
class Attendance extends Component
{
    public Student $student;

    #[Url]
    public ?string $month = null;

    public function mount(Student $student): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('parent'), 403);
        abort_unless($user->children()->whereKey($student->id)->exists(), 403, 'Bukan anak Anda.');
        $this->student = $student;
        $this->month = $this->month ?: Carbon::now()->format('Y-m');
    }

    public function changeMonth(int $diff): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->addMonths($diff)->format('Y-m');
    }

    public function render()
    {
        $start = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $records = $this->student->attendances()
            ->whereBetween('date', [$start, $end])
            ->get()
            ->keyBy(fn ($r) => $r->date->format('Y-m-d'));

        $counts = [
            'hadir' => $records->where('status', 'hadir')->count(),
            'izin' => $records->where('status', 'izin')->count(),
            'sakit' => $records->where('status', 'sakit')->count(),
            'alpa' => $records->where('status', 'alpa')->count(),
            'terlambat' => $records->where('status', 'terlambat')->count(),
        ];

        // Build calendar grid (Mon..Sun)
        $days = [];
        $firstDow = ($start->dayOfWeek + 6) % 7; // make Monday = 0
        for ($i = 0; $i < $firstDow; $i++) {
            $days[] = null;
        }
        for ($d = 1; $d <= $end->day; $d++) {
            $date = $start->copy()->day($d);
            $key = $date->format('Y-m-d');
            $days[] = [
                'day' => $d,
                'date' => $key,
                'record' => $records->get($key),
                'is_weekend' => in_array($date->dayOfWeek, [0, 6], true),
            ];
        }

        return view('livewire.portal.parent.attendance', [
            'days' => $days,
            'counts' => $counts,
            'monthLabel' => $start->translatedFormat('F Y'),
        ]);
    }
}
