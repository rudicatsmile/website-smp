<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\ClassAnnouncement;
use App\Models\ClassAssignment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Dashboard Siswa')]
class Dashboard extends Component
{
    public function render()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403, 'Akun siswa tidak terhubung.');

        $classId = $student->school_class_id;

        $assignments = ClassAssignment::query()
            ->published()
            ->where('school_class_id', $classId)
            ->with(['subject', 'teacher', 'submissions' => fn ($q) => $q->where('student_id', $student->id)])
            ->orderBy('due_at', 'asc')
            ->limit(6)
            ->get();

        $openCount = ClassAssignment::published()
            ->where('school_class_id', $classId)
            ->where(function ($q) {
                $q->whereNull('due_at')->orWhere('due_at', '>=', now());
            })
            ->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $student->id)->whereNotNull('submitted_at'))
            ->count();

        $overdueCount = ClassAssignment::published()
            ->where('school_class_id', $classId)
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $student->id)->whereNotNull('submitted_at'))
            ->count();

        $submittedCount = \App\Models\AssignmentSubmission::where('student_id', $student->id)
            ->whereNotNull('submitted_at')->count();

        $announcements = ClassAnnouncement::query()
            ->active()
            ->where(function ($q) use ($classId) {
                $q->whereNull('school_class_id')->orWhere('school_class_id', $classId);
            })
            ->with(['teacher', 'schoolClass'])
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        return view('livewire.portal.dashboard', compact(
            'student', 'assignments', 'announcements',
            'openCount', 'overdueCount', 'submittedCount'
        ));
    }
}
