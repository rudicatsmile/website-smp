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
#[Title('Buat Pengaduan Konseling')]
class CounselingCreate extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:64')]
    public string $category = '';

    #[Validate('required|string|max:120')]
    public string $subject = '';

    #[Validate('required|string|min:20|max:5000')]
    public string $body = '';

    #[Validate('required|in:low,medium,high,urgent')]
    public string $priority = 'medium';

    public array $files = [];

    public function submit()
    {
        $this->validate();
        $this->validate([
            'files.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        $user = auth()->user();
        abort_unless($user, 403);

        $paths = [];
        foreach ((array) $this->files as $file) {
            if ($file) $paths[] = $file->store('counseling', 'public');
        }

        $ticket = CounselingTicket::create([
            'user_id' => $user->id,
            'reporter_name' => $user->name,
            'reporter_contact' => $user->email,
            'category' => $this->category,
            'priority' => $this->priority,
            'status' => 'new',
            'subject' => $this->subject,
            'body' => $this->body,
            'attachments' => $paths ?: null,
            'channel' => 'portal',
            'is_anonymous' => false,
            'last_activity_at' => now(),
        ]);

        CounselingMessage::create([
            'counseling_ticket_id' => $ticket->id,
            'sender_type' => 'student',
            'user_id' => $user->id,
            'body' => $this->body,
            'attachments' => $paths ?: null,
        ]);

        session()->flash('success', 'Pengaduan terkirim. Kode tiket: '.$ticket->code);
        return redirect()->route('portal.counseling.show', $ticket->id);
    }

    public function render()
    {
        return view('livewire.portal.counseling-create', [
            'categories' => CounselingTicket::CATEGORIES,
            'priorities' => CounselingTicket::PRIORITIES,
        ]);
    }
}
