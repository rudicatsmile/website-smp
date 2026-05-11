<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\ParentNote;
use App\Models\ParentNoteMessage;
use App\Models\Student;
use App\Services\Notifications\ParentNoteNotifier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
#[Title('Topik Buku Penghubung Baru')]
class ParentNotesCreate extends Component
{
    use WithFileUploads;

    public Student $student;

    #[Validate('required|string|max:200')]
    public string $subject = '';

    #[Validate('required|string|max:32')]
    public string $category = 'akademik';

    #[Validate('required|in:low,medium,high')]
    public string $priority = 'medium';

    #[Validate('required|string|min:10|max:5000')]
    public string $body = '';

    public array $files = [];

    public function mount(Student $student): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('parent'), 403);
        abort_unless($user->children()->whereKey($student->id)->exists(), 403, 'Bukan anak Anda.');
        $this->student = $student->load('schoolClass');
    }

    public function submit()
    {
        $this->validate();
        $this->validate([
            'files.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        $paths = [];
        foreach ((array) $this->files as $file) {
            if ($file) {
                $paths[] = $file->store('parent-notes', 'public');
            }
        }

        $note = ParentNote::create([
            'student_id'           => $this->student->id,
            'school_class_id'      => $this->student->school_class_id,
            'homeroom_teacher_id'  => $this->student->schoolClass?->homeroom_teacher_id,
            'initiator_user_id'    => auth()->id(),
            'initiator_type'       => 'parent',
            'subject'              => $this->subject,
            'category'             => $this->category,
            'priority'             => $this->priority,
            'status'               => 'open',
            'last_activity_at'     => now(),
        ]);

        $message = ParentNoteMessage::create([
            'parent_note_id' => $note->id,
            'sender_type'    => 'parent',
            'user_id'        => auth()->id(),
            'body'           => $this->body,
            'attachments'    => $paths ?: null,
        ]);

        app(ParentNoteNotifier::class)->dispatchForMessage($message);

        session()->flash('success', 'Topik terkirim. Kode: ' . $note->code);
        return redirect()->route('portal.parent.notes.show', $note->id);
    }

    public function render()
    {
        return view('livewire.portal.parent.parent-notes-create', [
            'categories' => ParentNote::CATEGORIES,
            'priorities' => ParentNote::PRIORITIES,
        ]);
    }
}
