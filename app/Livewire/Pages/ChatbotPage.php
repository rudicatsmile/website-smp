<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Faq;
use App\Services\ChatbotService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ChatbotPage extends Component
{
    public string $message = '';
    public array $messages = [];
    public ?string $activeCategory = null;
    public string $sessionId;

    public function mount(): void
    {
        $this->sessionId = session()->getId() . '-page-' . uniqid();
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

    public function filterByCategory(?string $category): void
    {
        $this->activeCategory = $category;
    }

    public function markHelpful(ChatbotService $chatbot): void
    {
        $chatbot->markHelpful($this->sessionId);
        $this->messages[] = [
            'type' => 'bot',
            'text' => 'Terima kasih atas feedback-nya! Senang bisa membantu.',
            'results' => [],
        ];
    }

    public function markUnhelpful(ChatbotService $chatbot): void
    {
        $chatbot->markUnhelpful($this->sessionId);
        $this->messages[] = [
            'type' => 'bot',
            'text' => 'Maaf belum bisa membantu. Silakan hubungi kami via WhatsApp untuk bantuan lebih lanjut.',
            'results' => [],
        ];
    }

    #[Layout('layouts.app')]
    #[Title('FAQ - Tanya Jawab')]
    public function render()
    {
        $categories = Faq::active()->select('category')->distinct()->orderBy('category')->pluck('category');

        $faqs = Faq::active()->ordered()
            ->when($this->activeCategory, fn($q) => $q->where('category', $this->activeCategory))
            ->get()
            ->groupBy('category');

        return view('livewire.pages.chatbot-page', [
            'categories' => $categories,
            'faqs' => $faqs,
        ]);
    }
}
