<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Contact extends Component
{
    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email|max:160')]
    public string $email = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    #[Validate('required|string|max:160')]
    public string $subject = '';

    #[Validate('required|string|min:10|max:5000')]
    public string $message = '';

    public bool $sent = false;

    public function submit(GeneralSettings $settings): void
    {
        $this->validate();

        $msg = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        if ($settings->email) {
            try {
                Mail::to($settings->email)->send(new ContactMessageReceived($msg));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->sent = true;
    }

    #[Layout('layouts.app')]
    #[Title('Kontak')]
    public function render()
    {
        return view('livewire.pages.contact', [
            'settings' => app(GeneralSettings::class),
        ]);
    }
}
