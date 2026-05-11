<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use Illuminate\Support\Facades\View;

class MessageBuilder
{
    /**
     * Render a template view to plain text. Suitable for WhatsApp messages
     * and as plain-text email bodies. Pass key/value pairs in $data.
     */
    public function render(string $template, array $data): string
    {
        $view = "notifications.templates.{$template}";

        $defaults = [
            'school_name'    => config('notifications.school_name'),
            'school_phone'   => config('notifications.school_phone'),
            'school_website' => config('notifications.school_website'),
        ];

        $rendered = View::make($view, array_merge($defaults, $data))->render();

        // Normalize whitespace and trim
        $rendered = str_replace("\r\n", "\n", $rendered);
        $rendered = preg_replace("/\n{3,}/", "\n\n", $rendered);

        return trim((string) $rendered);
    }

    /**
     * Generate a sensible email subject line from the event.
     */
    public function subject(string $event, array $data): string
    {
        $school = config('notifications.school_name');

        return match ($event) {
            'absensi'      => "[{$school}] Absensi: {$data['student_name']} - {$data['status_label']}",
            'payment_due'  => "[{$school}] Pengingat Tagihan {$data['type_label']} - {$data['student_name']}",
            'announcement' => "[{$school}] Pengumuman: {$data['title']}",
            'rapor'        => "[{$school}] Pengambilan Rapor: {$data['student_name']}",
            'leave_request' => "[{$school}] Pengajuan Izin {$data['student_name']} - {$data['status_label']}",
            'parent_note'   => "[{$school}] Buku Penghubung: {$data['student_name']} - {$data['subject']}",
            default        => "[{$school}] Pemberitahuan",
        };
    }

    /**
     * Sanitize and normalize an Indonesian phone number for WhatsApp.
     * Returns null if invalid (less than 8 digits).
     */
    public function normalizePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if (strlen($digits) < 8) {
            return null;
        }
        // Convert local 08xxx → 628xxx
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        return $digits;
    }
}
