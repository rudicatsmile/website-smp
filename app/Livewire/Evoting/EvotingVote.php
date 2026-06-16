<?php

namespace App\Livewire\Evoting;

use App\Models\Election;
use App\Models\ElectionCandidate;
use App\Models\ElectionVote;
use App\Models\ElectionVoter;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class EvotingVote extends Component
{
    public $election;
    public $candidates;
    public $selectedCandidateId = null;
    public $selectedCandidateName = null;
    public $selectedCandidateNumber = null;
    public $showConfirmModal = false;

    public function mount()
    {
        $voterId = session('evoting_voter_id');
        $electionId = session('evoting_election_id');

        if (! $voterId || ! $electionId) {
            return redirect()->route('evoting.login');
        }

        $voter = ElectionVoter::find($voterId);

        if (! $voter || $voter->has_voted) {
            session()->forget(['evoting_voter_id', 'evoting_election_id']);
            return redirect()->route('evoting.login');
        }

        $this->election = Election::find($electionId);
        $this->candidates = ElectionCandidate::where('election_id', $electionId)
            ->orderBy('candidate_number')
            ->get();
    }

    public function confirmVote($candidateId)
    {
        $this->selectedCandidateId = $candidateId;
        $candidate = $this->candidates->firstWhere('id', $candidateId);
        
        if ($candidate) {
            $this->selectedCandidateName = $candidate->name;
            $this->selectedCandidateNumber = $candidate->candidate_number;
            $this->showConfirmModal = true;
        }
    }

    public function cancelVote()
    {
        $this->selectedCandidateId = null;
        $this->showConfirmModal = false;
    }

    public function submitVote()
    {
        \Illuminate\Support\Facades\Log::info('submitVote started', ['selectedCandidateId' => $this->selectedCandidateId]);

        if (! $this->selectedCandidateId) {
            \Illuminate\Support\Facades\Log::warning('submitVote failed: selectedCandidateId is empty');
            return;
        }

        $voterId = session('evoting_voter_id');
        $voter = ElectionVoter::find($voterId);

        \Illuminate\Support\Facades\Log::info('submitVote voter lookup', ['voterId' => $voterId, 'voter_exists' => (bool)$voter, 'has_voted' => $voter ? $voter->has_voted : null]);

        if ($voter && ! $voter->has_voted) {
            try {
                DB::transaction(function () use ($voter) {
                    \Illuminate\Support\Facades\Log::info('submitVote inside transaction');
                    // Catat Suara (Secret Ballot, tidak merekam ID pemilih)
                    ElectionVote::create([
                        'election_id' => $this->election->id,
                        'election_candidate_id' => $this->selectedCandidateId,
                    ]);

                    // Tandai pemilih sudah memilih
                    $voter->update([
                        'has_voted' => true,
                        'voted_at' => now(),
                    ]);
                });
                
                \Illuminate\Support\Facades\Log::info('submitVote transaction successful');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('submitVote exception: ' . $e->getMessage());
                throw $e;
            }

            // Hapus session
            session()->forget(['evoting_voter_id', 'evoting_election_id']);

            return redirect()->route('evoting.success');
        } else {
            \Illuminate\Support\Facades\Log::warning('submitVote failed: voter is null or has already voted');
        }
    }

    public function render()
    {
        return view('livewire.evoting.evoting-vote');
    }
}
