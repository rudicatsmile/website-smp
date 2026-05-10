<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\LeaveRequest;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Status Surat Izin')]
class PublicLeaveStatus extends Component
{
    #[Url(as: 'nis')]
    public string $nis = '';

    public string $notFound = '';

    public function mount(): void
    {
        // Auto search if NIS provided in URL
    }

    public function search(): void
    {
        $this->notFound = '';
    }

    public function render()
    {
        $items = collect();
        $student = null;

        if ($this->nis !== '') {
            $student = Student::where('nis', trim($this->nis))->first();
            if ($student) {
                $items = $student->leaveRequests()
                    ->with('reviewer')
                    ->orderByDesc('created_at')
                    ->limit(20)
                    ->get();
            } else {
                $this->notFound = 'NIS siswa tidak ditemukan.';
            }
        }

        return view('livewire.pages.public-leave-status', [
            'items'   => $items,
            'student' => $student,
        ]);
    }
}
