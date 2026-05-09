<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\CounselingMessage;
use App\Models\CounselingTicket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Konseling BK — Kirim Pengaduan')]
class CounselingForm extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:64')]
    public string $category = '';

    #[Validate('required|string|max:120')]
    public string $subject = '';

    #[Validate('required|string|min:20|max:5000')]
    public string $body = '';

    #[Validate('nullable|string|max:100')]
    public string $reporter_name = '';

    #[Validate('nullable|string|max:150')]
    public string $reporter_contact = '';

    public array $files = [];

    public ?CounselingTicket $submittedTicket = null;

    public function submit(): void
    {
        $this->validate([
            'files.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ] + $this->rules());

        $paths = [];
        foreach ((array) $this->files as $file) {
            if ($file) {
                $paths[] = $file->store('counseling', 'public');
            }
        }

        $ticket = CounselingTicket::create([
            'user_id' => null,
            'reporter_name' => $this->reporter_name ?: null,
            'reporter_contact' => $this->reporter_contact ?: null,
            'category' => $this->category,
            'priority' => 'medium',
            'status' => 'new',
            'subject' => $this->subject,
            'body' => $this->body,
            'attachments' => $paths ?: null,
            'channel' => 'public',
            'is_anonymous' => empty($this->reporter_name),
            'last_activity_at' => now(),
        ]);

        CounselingMessage::create([
            'counseling_ticket_id' => $ticket->id,
            'sender_type' => 'anonymous',
            'body' => $this->body,
            'attachments' => $paths ?: null,
        ]);

        $this->submittedTicket = $ticket;
        $this->reset(['category', 'subject', 'body', 'reporter_name', 'reporter_contact', 'files']);
    }

    public function render()
    {
        return view('livewire.pages.counseling-form', [
            'categories' => CounselingTicket::CATEGORIES,
        ]);
    }
}
