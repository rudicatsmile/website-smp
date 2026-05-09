<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\CounselingMessage;
use App\Models\CounselingTicket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Cek Status Konseling BK')]
class CounselingStatus extends Component
{
    #[Url(as: 'kode')]
    public string $code = '';

    public ?CounselingTicket $ticket = null;
    public string $notFound = '';

    #[Validate('required|string|min:10|max:3000')]
    public string $reply = '';

    public function mount(): void
    {
        if ($this->code) {
            $this->search();
        }
    }

    public function search(): void
    {
        $this->notFound = '';
        $this->ticket = null;

        $code = trim(strtoupper($this->code));
        if (! $code) {
            $this->notFound = 'Masukkan kode tiket.';
            return;
        }

        $ticket = CounselingTicket::where('code', $code)
            ->with(['publicMessages', 'assignee'])
            ->first();

        if (! $ticket) {
            $this->notFound = 'Kode tiket tidak ditemukan. Periksa kembali penulisan.';
            return;
        }

        $this->ticket = $ticket;
    }

    public function postReply(): void
    {
        $this->validate();
        if (! $this->ticket) return;

        if (in_array($this->ticket->status, ['resolved', 'closed'])) {
            session()->flash('error', 'Tiket sudah ditutup.');
            return;
        }

        CounselingMessage::create([
            'counseling_ticket_id' => $this->ticket->id,
            'sender_type' => 'anonymous',
            'body' => $this->reply,
        ]);

        $this->ticket->update(['last_activity_at' => now()]);
        $this->reply = '';
        $this->ticket = $this->ticket->fresh(['publicMessages', 'assignee']);
        session()->flash('success', 'Balasan terkirim.');
    }

    public function render()
    {
        return view('livewire.pages.counseling-status');
    }
}
