<?php

declare(strict_types=1);

namespace App\Livewire\Widgets;

use App\Services\ChatbotService;
use App\Settings\GeneralSettings;
use Livewire\Component;

class ChatbotWidget extends Component
{
    public bool $isOpen = false;
    public string $message = '';
    public array $messages = [];
    public string $sessionId;

    public function mount(): void
    {
        $this->sessionId = session()->getId() . '-' . uniqid();
        $this->messages[] = [
            'type' => 'bot',
            'text' => 'Halo! 👋 Saya asisten virtual SMP Al Wathoniyah 9. Silakan tanyakan seputar PPDB, biaya, fasilitas, atau info sekolah lainnya.',
            'results' => [],
        ];
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage(ChatbotService $chatbot): void
    {
        $this->message = trim($this->message);
        if (empty($this->message)) {
            return;
        }

        $userMsg = $this->message;
        $this->messages[] = ['type' => 'user', 'text' => $userMsg, 'results' => []];

        $result = $chatbot->processMessage($this->sessionId, $userMsg);

        $this->messages[] = [
            'type' => 'bot',
            'text' => $result['message'],
            'results' => $result['results'],
            'responseType' => $result['type'],
        ];

        $this->message = '';
    }

    public function markHelpful(ChatbotService $chatbot): void
    {
        $chatbot->markHelpful($this->sessionId);
        $this->messages[] = [
            'type' => 'bot',
            'text' => 'Terima kasih atas feedback-nya! 😊 Senang bisa membantu.',
            'results' => [],
        ];
    }

    public function markUnhelpful(ChatbotService $chatbot): void
    {
        $chatbot->markUnhelpful($this->sessionId);
        $settings = app(GeneralSettings::class);
        $wa = $settings->whatsapp;
        $waClean = $wa ? preg_replace('/\D/', '', $wa) : null;

        $msg = 'Maaf belum bisa membantu. 😔';
        if ($waClean) {
            $msg .= ' Silakan hubungi kami langsung via WhatsApp untuk bantuan lebih lanjut.';
        }
        $this->messages[] = [
            'type' => 'bot',
            'text' => $msg,
            'results' => [],
        ];
    }

    public function render()
    {
        return view('livewire.widgets.chatbot-widget', [
            'settings' => app(GeneralSettings::class),
        ]);
    }
}
