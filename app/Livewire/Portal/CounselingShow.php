<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\CounselingMessage;
use App\Models\CounselingTicket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
#[Title('Detail Tiket Konseling')]
class CounselingShow extends Component
{
    use WithFileUploads;

    public CounselingTicket $ticket;

    #[Validate('required|string|min:2|max:3000')]
    public string $reply = '';

    public array $files = [];

    public function mount(CounselingTicket $ticket): void
    {
        $user = auth()->user();
        abort_unless($user && $ticket->user_id === $user->id, 403);

        $this->ticket = $ticket;
    }

    public function postReply(): void
    {
        $this->validate();
        $this->validate([
            'files.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        if (in_array($this->ticket->status, ['resolved', 'closed'])) {
            session()->flash('error', 'Tiket sudah ditutup.');
            return;
        }

        $paths = [];
        foreach ((array) $this->files as $file) {
            if ($file) $paths[] = $file->store('counseling', 'public');
        }

        CounselingMessage::create([
            'counseling_ticket_id' => $this->ticket->id,
            'sender_type' => 'student',
            'user_id' => auth()->id(),
            'body' => $this->reply,
            'attachments' => $paths ?: null,
        ]);

        $this->ticket->update(['last_activity_at' => now()]);
        $this->reply = '';
        $this->files = [];
    }

    public function closeTicket(): void
    {
        $this->ticket->update([
            'status' => 'closed',
            'last_activity_at' => now(),
        ]);
    }

    public function render()
    {
        $messages = $this->ticket->publicMessages()->with(['staffMember', 'user'])->get();
        return view('livewire.portal.counseling-show', [
            'ticket' => $this->ticket,
            'messages' => $messages,
        ]);
    }
}
