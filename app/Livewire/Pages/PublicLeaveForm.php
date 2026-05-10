<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\LeaveRequest;
use App\Models\Student;
use App\Services\Attendance\LeaveRequestService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Surat Izin Online')]
class PublicLeaveForm extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:32')]
    public string $nis = '';

    #[Validate('required|string|max:120')]
    public string $parent_name = '';

    #[Validate('nullable|string|max:32')]
    public string $parent_phone = '';

    #[Validate('required|in:sakit,izin,dinas')]
    public string $type = 'izin';

    #[Validate('required|date')]
    public string $date_from = '';

    #[Validate('required|date|after_or_equal:date_from')]
    public string $date_to = '';

    #[Validate('required|string|min:10|max:2000')]
    public string $reason = '';

    public $attachment = null;

    public function mount(): void
    {
        $this->date_from = Carbon::today()->toDateString();
        $this->date_to = Carbon::today()->toDateString();
    }

    public function submit()
    {
        $this->validate();
        $this->validate([
            'attachment' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        $student = Student::where('nis', trim($this->nis))->first();
        if (! $student) {
            $this->addError('nis', 'NIS siswa tidak ditemukan.');
            return;
        }

        // Soft verification: parent_name should match if data is on file
        if ($student->parent_name && mb_strtolower(trim($student->parent_name)) !== mb_strtolower(trim($this->parent_name))) {
            $this->addError('parent_name', 'Nama orang tua tidak cocok dengan data yang terdaftar untuk siswa ini.');
            return;
        }

        $from = Carbon::parse($this->date_from)->startOfDay();
        $to = Carbon::parse($this->date_to)->startOfDay();
        if ($from->diffInDays($to) > 14) {
            $this->addError('date_to', 'Maksimal pengajuan 14 hari berturut-turut.');
            return;
        }

        $service = app(LeaveRequestService::class);
        if ($service->hasOverlap($student->id, $from, $to)) {
            $this->addError('date_from', 'Sudah ada pengajuan izin pending/approved untuk tanggal yang tumpang tindih.');
            return;
        }

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('leave-requests', 'public');
        }

        $req = LeaveRequest::create([
            'student_id'           => $student->id,
            'submitted_by_user_id' => null,
            'type'                 => $this->type,
            'date_from'            => $from,
            'date_to'              => $to,
            'reason'               => $this->reason,
            'attachment'           => $path,
            'status'               => 'pending',
            'submission_channel'   => 'public',
            'submitter_name'       => $this->parent_name,
            'submitter_phone'      => $this->parent_phone ?: null,
        ]);

        session()->flash('leave_success', "Pengajuan berhasil dikirim. No. Tiket: #{$req->id}. Anda akan menerima notifikasi setelah sekolah meninjau pengajuan.");

        return redirect()->route('izin.status', ['nis' => $student->nis]);
    }

    public function render()
    {
        return view('livewire.pages.public-leave-form', [
            'types' => LeaveRequest::TYPES,
        ]);
    }
}
