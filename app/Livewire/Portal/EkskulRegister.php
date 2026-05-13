<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Daftar Ekstrakurikuler')]
class EkskulRegister extends Component
{
    public Extracurricular $ekskul;
    public string $note = '';

    public function mount(Extracurricular $ekskul): void
    {
        abort_unless($ekskul->is_active, 404);

        $student = auth()->user()?->student;

        if (! $student) {
            session()->flash('error', 'Fitur ini hanya untuk siswa yang terdaftar.');
            $this->redirect(route('portal.ekskul.index'), navigate: true);
            return;
        }

        $alreadyRegistered = ExtracurricularMember::where('extracurricular_id', $ekskul->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadyRegistered) {
            session()->flash('info', 'Kamu sudah pernah mendaftar ekskul ini.');
            $this->redirect(route('portal.ekskul.index'), navigate: true);
            return;
        }

        $this->ekskul = $ekskul->load(['coach', 'schedules']);
    }

    public function submit(): void
    {
        $this->validate(['note' => 'nullable|string|max:500']);

        $student = auth()->user()?->student;
        abort_if(! $student, 403);

        if ($this->ekskul->quota) {
            $approved = $this->ekskul->members()->where('status', 'approved')->count();
            if ($approved >= $this->ekskul->quota) {
                session()->flash('error', 'Maaf, kuota ekskul ini sudah penuh.');
                return;
            }
        }

        ExtracurricularMember::create([
            'extracurricular_id' => $this->ekskul->id,
            'student_id'         => $student->id,
            'status'             => 'pending',
            'note'               => $this->note,
        ]);

        session()->flash('success', 'Pendaftaran berhasil dikirim! Tunggu persetujuan pembina.');
        $this->redirect(route('portal.ekskul.index'), navigate: true);
    }

    public function render()
    {
        if (! isset($this->ekskul)) {
            return '<div></div>';
        }

        return view('livewire.portal.ekskul-register');
    }
}
