<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal-auth')]
#[Title('Login Portal Siswa')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectAfterLogin();
        }
    }

    public function submit(): void
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        $user = Auth::user();
        if (! $user->hasRole('student') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            Auth::logout();
            $this->addError('email', 'Akun ini bukan akun siswa.');
            return;
        }

        $this->redirectAfterLogin();
    }

    protected function redirectAfterLogin(): void
    {
        $this->redirectRoute('portal.dashboard', navigate: false);
    }

    public function render()
    {
        return view('livewire.portal.login');
    }
}
