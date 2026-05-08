<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\SpmbRegistration;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SpmbStatus extends Component
{
    #[Validate('required|string|max:50')]
    public string $registration_number = '';

    #[Validate('required|date')]
    public string $birth_date = '';

    public ?SpmbRegistration $found = null;
    public ?string $error = null;

    public function check(): void
    {
        $this->validate();
        $this->error = null;

        $reg = SpmbRegistration::where('registration_number', $this->registration_number)
            ->whereDate('birth_date', $this->birth_date)
            ->with('period')
            ->first();

        if (! $reg) {
            $this->found = null;
            $this->error = 'Data tidak ditemukan. Periksa kembali nomor pendaftaran & tanggal lahir.';
            return;
        }
        $this->found = $reg;
    }

    #[Layout('layouts.app')]
    #[Title('Cek Status SPMB')]
    public function render()
    {
        return view('livewire.pages.spmb-status');
    }
}
