<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\LeaveRequest;
use App\Models\Student;
use App\Services\Attendance\LeaveRequestService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal')]
#[Title('Ajukan Surat Izin')]
class LeaveRequestCreate extends Component
{
    use WithFileUploads;

    public Student $student;

    #[Validate('required|in:sakit,izin,dinas')]
    public string $type = 'izin';

    #[Validate('required|date')]
    public string $date_from = '';

    #[Validate('required|date|after_or_equal:date_from')]
    public string $date_to = '';

    #[Validate('required|string|min:10|max:2000')]
    public string $reason = '';

    public $attachment = null;

    public function mount(Student $student): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('parent'), 403);
        abort_unless($user->children()->whereKey($student->id)->exists(), 403, 'Bukan anak Anda.');
        $this->student = $student;
        $this->date_from = Carbon::today()->toDateString();
        $this->date_to = Carbon::today()->toDateString();
    }

    public function submit()
    {
        $this->validate();
        $this->validate([
            'attachment' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        $from = Carbon::parse($this->date_from)->startOfDay();
        $to = Carbon::parse($this->date_to)->startOfDay();

        if ($from->diffInDays($to) > 14) {
            $this->addError('date_to', 'Maksimal pengajuan 14 hari berturut-turut.');
            return;
        }

        $service = app(LeaveRequestService::class);
        if ($service->hasOverlap($this->student->id, $from, $to)) {
            $this->addError('date_from', 'Sudah ada pengajuan izin pending/approved untuk tanggal yang tumpang tindih.');
            return;
        }

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('leave-requests', 'public');
        }

        $req = LeaveRequest::create([
            'student_id'           => $this->student->id,
            'submitted_by_user_id' => auth()->id(),
            'type'                 => $this->type,
            'date_from'            => $from,
            'date_to'              => $to,
            'reason'               => $this->reason,
            'attachment'           => $path,
            'status'               => 'pending',
            'submission_channel'   => 'portal',
            'submitter_name'       => auth()->user()->name,
        ]);

        session()->flash('leave_success', "Pengajuan berhasil dikirim (Tiket #{$req->id}). Anda akan menerima notifikasi WhatsApp/Email setelah sekolah meninjau.");

        return redirect()->route('portal.parent.leave.index', ['student' => $this->student->slug]);
    }

    public function render()
    {
        return view('livewire.portal.parent.leave-request-create', [
            'types' => LeaveRequest::TYPES,
        ]);
    }
}
