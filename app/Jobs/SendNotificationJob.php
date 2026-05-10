<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\NotificationLog;
use App\Services\Notifications\Channels\MailChannel;
use App\Services\Notifications\Channels\WablasChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries;
    public int $backoff;

    public function __construct(public int $logId)
    {
        $this->tries = (int) config('notifications.queue.retries', 3);
        $this->backoff = (int) config('notifications.queue.retry_after_seconds', 60);
    }

    public function handle(WablasChannel $wablas, MailChannel $mail): void
    {
        /** @var NotificationLog|null $log */
        $log = NotificationLog::find($this->logId);
        if (! $log) {
            return;
        }

        // Skip if already sent (idempotent on retries)
        if ($log->status === 'sent') {
            return;
        }

        if ($log->channel === 'whatsapp') {
            $result = $wablas->send((string) $log->recipient_phone, (string) $log->message);
        } elseif ($log->channel === 'email') {
            $result = $mail->send(
                (string) $log->recipient_email,
                (string) ($log->subject ?? 'Pemberitahuan'),
                (string) $log->message,
            );
        } else {
            $log->update(['status' => 'failed', 'error' => 'Unknown channel: ' . $log->channel]);
            return;
        }

        if ($result->success) {
            $log->update([
                'status'  => 'sent',
                'sent_at' => now(),
                'payload' => $result->payload,
                'error'   => null,
            ]);

            // Throttle WA bursts
            if ($log->channel === 'whatsapp') {
                $delayMs = (int) config('notifications.wablas.delay_ms_between_messages', 0);
                if ($delayMs > 0) {
                    usleep($delayMs * 1000);
                }
            }
        } else {
            $log->update([
                'status'  => 'failed',
                'error'   => $result->error,
                'payload' => $result->payload,
            ]);

            // Re-throw so the queue worker can retry according to $tries
            throw new \RuntimeException((string) $result->error);
        }
    }

    public function failed(\Throwable $exception): void
    {
        $log = NotificationLog::find($this->logId);
        if ($log && $log->status !== 'sent') {
            $log->update([
                'status' => 'failed',
                'error'  => $exception->getMessage(),
            ]);
        }
    }
}
