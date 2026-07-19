<?php

namespace App\Livewire\Mobile\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.mobile')]
#[Title('Login - Mobile')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('mobile.dashboard');
        }
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended(route('mobile.dashboard'));
        }

        $this->addError('email', 'Email atau kata sandi salah.');
    }

    public function render()
    {
        return view('livewire.mobile.auth.login');
    }
}
