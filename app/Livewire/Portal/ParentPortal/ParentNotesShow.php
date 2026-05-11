<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\ParentNote;
use App\Models\ParentNoteMessage;
use App\Services\Notifications\ParentNoteNotifier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
#[Title('Detail Buku Penghubung')]
class ParentNotesShow extends Component
{
    use WithFileUploads;

    public ParentNote $note;

    #[Validate('required|string|min:2|max:3000')]
    public string $reply = '';

    public array $files = [];

    public function mount(ParentNote $note): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('parent'), 403);
        abort_unless(
            $note->student?->parents()->whereKey($user->id)->exists(),
            403,
            'Topik ini bukan milik anak Anda.'
        );

        $this->note = $note->load(['student', 'schoolClass', 'homeroomTeacher']);

        // Mark teacher messages as read by parent
        ParentNoteMessage::where('parent_note_id', $this->note->id)
            ->where('is_internal', false)
            ->where('sender_type', 'teacher')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function postReply(): void
    {
        $this->validate();
        $this->validate([
            'files.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        if (in_array($this->note->status, ['resolved', 'closed'])) {
            session()->flash('error', 'Topik ini sudah ditutup.');
            return;
        }

        $paths = [];
        foreach ((array) $this->files as $file) {
            if ($file) {
                $paths[] = $file->store('parent-notes', 'public');
            }
        }

        $message = ParentNoteMessage::create([
            'parent_note_id' => $this->note->id,
            'sender_type'    => 'parent',
            'user_id'        => auth()->id(),
            'body'           => $this->reply,
            'attachments'    => $paths ?: null,
        ]);

        $updates = ['last_activity_at' => now()];
        if ($this->note->status === 'replied') {
            $updates['status'] = 'open';
        }
        $this->note->update($updates);
        $this->note->refresh();

        app(ParentNoteNotifier::class)->dispatchForMessage($message);

        $this->reply = '';
        $this->files = [];
    }

    public function closeNote(): void
    {
        $this->note->update([
            'status' => 'closed',
            'resolved_at' => now(),
            'last_activity_at' => now(),
        ]);
        $this->note->refresh();
    }

    public function render()
    {
        $messages = $this->note->publicMessages()->with(['staffMember', 'user'])->get();

        return view('livewire.portal.parent.parent-notes-show', [
            'note' => $this->note,
            'messages' => $messages,
        ]);
    }
}
