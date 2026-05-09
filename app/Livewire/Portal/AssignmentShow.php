<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\AssignmentSubmission;
use App\Models\ClassAssignment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
#[Title('Detail Tugas')]
class AssignmentShow extends Component
{
    use WithFileUploads;

    public ClassAssignment $assignment;

    /** @var array */
    public $files = [];

    public string $note = '';

    public function mount(string $slug): void
    {
        $this->assignment = ClassAssignment::where('slug', $slug)->firstOrFail();
        $student = auth()->user()->student;
        abort_unless($student && $student->school_class_id === $this->assignment->school_class_id, 403);

        $sub = $this->assignment->submissionFor($student);
        if ($sub) {
            $this->note = (string) ($sub->note ?? '');
        }
    }

    public function submit(): void
    {
        $this->validate([
            'files.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip,txt',
            'note' => 'nullable|string|max:2000',
        ]);

        $student = auth()->user()->student;
        abort_unless($student, 403);

        $paths = [];
        foreach ($this->files as $file) {
            $paths[] = $file->store('submissions', 'public');
        }

        $existing = $this->assignment->submissionFor($student);
        $data = [
            'note' => $this->note ?: null,
            'submitted_at' => now(),
        ];
        if (!empty($paths)) {
            $data['files'] = array_merge($existing?->files ?? [], $paths);
        }

        AssignmentSubmission::updateOrCreate(
            ['class_assignment_id' => $this->assignment->id, 'student_id' => $student->id],
            $data,
        );

        $this->files = [];
        session()->flash('success', 'Tugas berhasil dikumpulkan!');
        $this->redirectRoute('portal.assignments.show', $this->assignment->slug, navigate: false);
    }

    public function render()
    {
        $student = auth()->user()->student;
        $submission = $this->assignment->submissionFor($student);
        $this->assignment->load(['subject', 'teacher', 'schoolClass']);

        return view('livewire.portal.assignment-show', compact('submission', 'student'));
    }
}
