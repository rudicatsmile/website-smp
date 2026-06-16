<?php

namespace App\Livewire\Evoting;

use App\Models\Election;
use App\Models\ElectionVoter;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class EvotingLogin extends Component
{
    public string $nisn = '';
    public string $token = '';

    public function login()
    {
        $this->validate([
            'nisn' => 'required',
            'token' => 'required',
        ]);

        $election = Election::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (! $election) {
            $this->addError('token', 'Tidak ada pemilihan OSIS yang sedang aktif saat ini.');
            return;
        }

        $student = Student::where('nisn', $this->nisn)->first();

        if (! $student) {
            $this->addError('nisn', 'NISN tidak ditemukan di dalam sistem.');
            return;
        }

        $voter = ElectionVoter::where('election_id', $election->id)
            ->where('student_id', $student->id)
            ->where('token', strtoupper($this->token))
            ->first();

        if (! $voter) {
            $this->addError('token', 'Token tidak valid atau tidak cocok dengan NISN.');
            return;
        }

        if ($voter->has_voted) {
            $this->addError('token', 'Anda sudah memberikan suara pada pemilihan ini.');
            return;
        }

        // Set session for logged-in voter
        session()->put('evoting_voter_id', $voter->id);
        session()->put('evoting_election_id', $election->id);

        return redirect()->route('evoting.vote');
    }

    public function render()
    {
        return view('livewire.evoting.evoting-login');
    }
}
