<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Models\LessonSession;
use Illuminate\Support\Facades\Log;

class LessonReminderNotifier
{
    public function __construct(protected NotificationService $notifier) {}

    /**
     * Dispatch reminders for sessions starting within the configured window.
     * Returns count of notifications queued.
     */
    public function dispatchUpcoming(): int
    {
        $config = config('notifications.events.lesson_reminder');
        if (empty($config['enabled'])) {
            return 0;
        }

        $minutesBefore = (int) ($config['minutes_before'] ?? 30);
        $windowStart = now()->addMinutes($minutesBefore - 5);
        $windowEnd = now()->addMinutes($minutesBefore + 5);

        $sessions = LessonSession::query()
            ->with(['schoolClass', 'subject', 'teacher'])
            ->where('status', 'published')
            ->where('session_date', today())
            ->whereBetween('start_time', [$windowStart->format('H:i:s'), $windowEnd->format('H:i:s')])
            ->get();

        $count = 0;
        $channels = $config['channels'] ?? ['whatsapp', 'email'];

        foreach ($sessions as $session) {
            $teacher = $session->teacher;
            if (! $teacher) continue;

            $phone = $teacher->whatsapp ?: $teacher->phone;
            $email = $teacher->email;
            if (! $phone && ! $email) continue;

            $data = [
                'recipient_name' => $teacher->name,
                'subject_name' => $session->subject?->name ?? '-',
                'class_name' => $session->schoolClass?->name ?? '-',
                'time_range' => $session->time_range,
                'topic' => $session->topic,
                'period' => $session->period,
                'url' => url('/admin/lesson-sessions/' . $session->id . '/edit'),
            ];

            try {
                $this->notifier->sendCustom(
                    recipient: [
                        'name' => $teacher->name,
                        'phone' => $phone,
                        'email' => $email,
                    ],
                    channels: $channels,
                    subject: app(MessageBuilder::class)->subject('lesson_reminder', $data),
                    body: app(MessageBuilder::class)->render('lesson-reminder', $data),
                    event: 'lesson_reminder',
                    notifiable: $session,
                    triggeredBy: null,
                );
                $count++;
            } catch (\Throwable $e) {
                Log::warning('[LessonReminder] gagal queue: ' . $e->getMessage(), [
                    'session_id' => $session->id,
                ]);
            }
        }

        return $count;
    }
}
