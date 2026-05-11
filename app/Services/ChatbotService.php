<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ChatLog;
use App\Models\Faq;
use App\Settings\GeneralSettings;
use Illuminate\Support\Str;

class ChatbotService
{
    private const MIN_MATCH_SCORE = 1;
    private const MAX_RESULTS = 3;

    public function processMessage(string $sessionId, string $message): array
    {
        $message = trim($message);
        $normalized = $this->normalize($message);

        $faqs = Faq::active()->ordered()->get();
        $matches = [];

        foreach ($faqs as $faq) {
            $score = $this->calculateScore($normalized, $faq);
            if ($score >= self::MIN_MATCH_SCORE) {
                $matches[] = ['faq' => $faq, 'score' => $score];
            }
        }

        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);
        $matches = array_slice($matches, 0, self::MAX_RESULTS);

        if (empty($matches)) {
            $response = $this->fallbackResponse();
            $this->log($sessionId, $message, $response, null);
            return [
                'type' => 'fallback',
                'message' => $response,
                'results' => [],
            ];
        }

        $best = $matches[0];
        $results = array_map(fn($m) => [
            'id' => $m['faq']->id,
            'question' => $m['faq']->question,
            'answer' => $m['faq']->answer,
            'category' => $m['faq']->category,
            'score' => $m['score'],
        ], $matches);

        $response = count($matches) === 1
            ? $best['faq']->answer
            : $this->multipleMatchResponse($results);

        $this->log($sessionId, $message, $response, (string) $best['faq']->id);

        return [
            'type' => count($matches) === 1 ? 'exact' : 'multiple',
            'message' => $response,
            'results' => $results,
        ];
    }

    public function markHelpful(string $sessionId): void
    {
        ChatLog::where('session_id', $sessionId)
            ->latest()
            ->first()
            ?->update(['was_helpful' => true]);
    }

    public function markUnhelpful(string $sessionId): void
    {
        ChatLog::where('session_id', $sessionId)
            ->latest()
            ->first()
            ?->update(['was_helpful' => false]);
    }

    private function calculateScore(string $normalized, Faq $faq): int
    {
        $score = 0;
        $keywords = $faq->keywords_array;
        $questionWords = $this->tokenize($faq->question);
        $answerWords = $this->tokenize($faq->answer);
        $userWords = $this->tokenize($normalized);

        foreach ($keywords as $keyword) {
            $kw = $this->normalize($keyword);
            if (str_contains($normalized, $kw)) {
                $score += 3;
            }
        }

        foreach ($userWords as $word) {
            if (strlen($word) < 3) continue;
            if (in_array($word, $questionWords, true)) {
                $score += 2;
            }
            if (in_array($word, $answerWords, true)) {
                $score += 1;
            }
        }

        $categoryMap = [
            'ppdb' => ['ppdb', 'daftar', 'pendaftaran', 'masuk', 'calon', 'siswa baru', 'spmb'],
            'biaya' => ['biaya', 'spp', 'bayar', 'harga', 'uang', 'gratis', 'beasiswa'],
            'fasilitas' => ['fasilitas', 'gedung', 'lab', 'laboratorium', 'komputer', 'perpustakaan', 'lapangan'],
            'akademik' => ['belajar', 'guru', 'pelajaran', 'mapel', 'ekstrakurikuler', 'ekskul', 'prestasi'],
            'umum' => ['alamat', 'lokasi', 'telepon', 'kontak', 'jam', 'waktu', 'seragam'],
        ];

        foreach ($categoryMap as $cat => $catKeywords) {
            foreach ($catKeywords as $ck) {
                if (str_contains($normalized, $ck)) {
                    if ($faq->category === $cat) {
                        $score += 2;
                    }
                }
            }
        }

        return $score;
    }

    private function normalize(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function tokenize(string $text): array
    {
        $normalized = $this->normalize($text);
        $words = explode(' ', $normalized);
        return array_filter($words, fn($w) => strlen($w) >= 2);
    }

    private function fallbackResponse(): string
    {
        $settings = app(GeneralSettings::class);
        $wa = $settings->whatsapp;
        $waClean = $wa ? preg_replace('/\D/', '', $wa) : null;

        $messages = [
            'Maaf, saya belum bisa menjawab pertanyaan itu. Coba gunakan kata kunci lain seperti "biaya SPP", "cara daftar", atau "fasilitas sekolah".',
            'Pertanyaan Anda belum ada di database saya. Silakan coba kata kunci yang lebih spesifik.',
            'Saya belum mengerti pertanyaan tersebut. Anda bisa tanyakan tentang PPDB, biaya, atau fasilitas sekolah.',
        ];

        $msg = $messages[array_rand($messages)];

        if ($waClean) {
            $msg .= "\n\nAnda juga bisa menghubungi kami langsung via WhatsApp untuk pertanyaan lebih lanjut.";
        }

        return $msg;
    }

    private function multipleMatchResponse(array $results): string
    {
        $response = "Saya menemukan beberapa jawaban yang mungkin sesuai:\n\n";
        foreach ($results as $i => $r) {
            $num = $i + 1;
            $response .= "{$num}. *{$r['question']}*\n{$r['answer']}\n\n";
        }
        return trim($response);
    }

    private function log(string $sessionId, string $userMessage, string $botResponse, ?string $faqId): void
    {
        ChatLog::create([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'bot_response' => $botResponse,
            'matched_faq_id' => $faqId,
        ]);
    }
}
