<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Jobs\SendNotificationJob;
use App\Models\NotificationLog;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    public function __construct(
        protected MessageBuilder $builder,
    ) {}

    /**
     * Queue a notification for a Student (or any Model with parent_phone/email).
     * Creates one NotificationLog row per channel and dispatches the send job.
     *
     * @param array<string> $channels  Subset of ['whatsapp','email']
     * @param string $template         Blade template name (without "notifications.templates." prefix)
     * @param string $event            Event tag (absensi, payment_due, announcement, manual, rapor)
     * @param array  $data             Variables for the template
     * @param Model|null $notifiable   Optional related model for log audit
     * @param int|null $triggeredBy    user_id triggering this notif (null = system)
     */
    public function notifyStudentParent(
        Student $student,
        array $channels,
        string $template,
        string $event,
        array $data,
        ?Model $notifiable = null,
        ?int $triggeredBy = null,
    ): array {
        $logs = [];

        $body = $this->builder->render($template, $data);
        $subject = $this->builder->subject($event, $data);

        $parentName = $student->parent_name;
        $phone = $this->builder->normalizePhone($student->parent_phone);
        $email = $student->parent_email;

        $eventConfig = config('notifications.events.' . $event);
        $eventEnabled = $eventConfig['enabled'] ?? true;
        if (! $eventEnabled) {
            return [];
        }
        $allowedChannels = $eventConfig['channels'] ?? ['whatsapp', 'email'];
        $channels = array_values(array_intersect($channels, $allowedChannels));

        $notifiable = $notifiable ?? $student;

        foreach ($channels as $channel) {
            // Skip if recipient address missing
            if ($channel === 'whatsapp' && ! $phone) {
                continue;
            }
            if ($channel === 'email' && ! $email) {
                continue;
            }

            $log = NotificationLog::create([
                'notifiable_type' => $notifiable::class,
                'notifiable_id'   => $notifiable->getKey(),
                'recipient_name'  => $parentName,
                'recipient_phone' => $channel === 'whatsapp' ? $phone : null,
                'recipient_email' => $channel === 'email' ? $email : null,
                'channel'         => $channel,
                'event'           => $event,
                'subject'         => $subject,
                'message'         => $body,
                'status'          => 'pending',
                'triggered_by'    => $triggeredBy,
            ]);

            SendNotificationJob::dispatch($log->id)
                ->onConnection(config('notifications.queue.connection'))
                ->onQueue(config('notifications.queue.name'));

            $logs[] = $log;
        }

        return $logs;
    }

    /**
     * Send a free-form notification (used for announcements / manual blasts).
     *
     * @param array $recipient  ['name' => ..., 'phone' => ..., 'email' => ...]
     */
    public function sendCustom(
        array $recipient,
        array $channels,
        string $subject,
        string $body,
        string $event = 'manual',
        ?Model $notifiable = null,
        ?int $triggeredBy = null,
    ): array {
        $logs = [];

        $phone = $this->builder->normalizePhone($recipient['phone'] ?? null);
        $email = $recipient['email'] ?? null;

        foreach ($channels as $channel) {
            if ($channel === 'whatsapp' && ! $phone) {
                continue;
            }
            if ($channel === 'email' && ! $email) {
                continue;
            }

            $log = NotificationLog::create([
                'notifiable_type' => $notifiable ? $notifiable::class : null,
                'notifiable_id'   => $notifiable?->getKey(),
                'recipient_name'  => $recipient['name'] ?? null,
                'recipient_phone' => $channel === 'whatsapp' ? $phone : null,
                'recipient_email' => $channel === 'email' ? $email : null,
                'channel'         => $channel,
                'event'           => $event,
                'subject'         => $subject,
                'message'         => $body,
                'status'          => 'pending',
                'triggered_by'    => $triggeredBy,
            ]);

            SendNotificationJob::dispatch($log->id)
                ->onConnection(config('notifications.queue.connection'))
                ->onQueue(config('notifications.queue.name'));

            $logs[] = $log;
        }

        return $logs;
    }
}
