<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\Student;
use App\Models\StudentPayment;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Dashboard Orang Tua')]
class Dashboard extends Component
{
    public function mount(): void
    {
        abort_unless(auth()->user()?->hasRole('parent'), 403, 'Bukan akun orang tua.');
    }

    public function render()
    {
        $user = auth()->user();
        $children = $user->children()->with('schoolClass')->get();

        $summary = [];
        foreach ($children as $child) {
            $summary[$child->id] = $this->summarizeChild($child);
        }

        return view('livewire.portal.parent.dashboard', [
            'children' => $children,
            'summary' => $summary,
        ]);
    }

    protected function summarizeChild(Student $child): array
    {
        $unpaid = $child->payments()->unpaid()->sum('amount');
        $unpaidCount = $child->payments()->unpaid()->count();

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $attMonth = $child->attendances()
            ->whereBetween('date', [$monthStart, $monthEnd])->get();
        $totalDays = $attMonth->count();
        $presentDays = $attMonth->whereIn('status', ['hadir', 'terlambat'])->count();
        $attendancePct = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        $avgNilai = $child->grades()->whereNotNull('nilai_akhir')->avg('nilai_akhir');

        $violationPoints = (int) $child->violations()->sum('points');

        $unreadNotes = \App\Models\ParentNoteMessage::query()
            ->whereHas('note', fn ($q) => $q->where('student_id', $child->id))
            ->where('sender_type', 'teacher')
            ->where('is_internal', false)
            ->whereNull('read_at')
            ->count();

        return [
            'unpaid_total' => (int) $unpaid,
            'unpaid_count' => (int) $unpaidCount,
            'attendance_pct' => $attendancePct,
            'avg_grade' => $avgNilai ? round((float) $avgNilai, 1) : null,
            'violation_points' => $violationPoints,
            'unread_notes' => $unreadNotes,
        ];
    }
}
