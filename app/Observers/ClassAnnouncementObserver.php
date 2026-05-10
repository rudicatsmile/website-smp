<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ClassAnnouncement;
use App\Models\Student;
use App\Services\Notifications\NotificationService;
use App\Services\Notifications\MessageBuilder;
use Illuminate\Support\Facades\Log;

class ClassAnnouncementObserver
{
    public function __construct(
        protected NotificationService $service,
        protected MessageBuilder $builder,
    ) {}

    public function created(ClassAnnouncement $a): void
    {
        $this->maybeBroadcast($a);
    }

    public function updated(ClassAnnouncement $a): void
    {
        // Re-broadcast only if it just transitioned to published OR notify flags
        // were just enabled, AND has not been sent yet.
        if (
            $a->wasChanged(['is_published', 'notify_wa', 'notify_email', 'published_at'])
        ) {
            $this->maybeBroadcast($a);
        }
    }

    protected function maybeBroadcast(ClassAnnouncement $a): void
    {
        $config = config('notifications.events.announcement');
        if (empty($config['enabled'])) {
            return;
        }

        if (! $a->is_published) {
            return;
        }
        if ($a->notification_sent_at) {
            return; // already broadcasted
        }
        if (! $a->notify_wa && ! $a->notify_email) {
            return;
        }

        $channels = [];
        if ($a->notify_wa)    $channels[] = 'whatsapp';
        if ($a->notify_email) $channels[] = 'email';
        if (empty($channels)) {
            return;
        }

        // Determine target students: specific class or all active
        $studentsQuery = Student::query()->active();
        if ($a->school_class_id) {
            $studentsQuery->where('school_class_id', $a->school_class_id);
        }
        $students = $studentsQuery->get();
        if ($students->isEmpty()) {
            return;
        }

        $bodyPlain = trim(strip_tags((string) $a->body));
        $publishedAt = optional($a->published_at)->translatedFormat('d F Y H:i');
        $url = url('/admin/class-announcements/' . $a->id);

        foreach ($students as $student) {
            try {
                $this->service->notifyStudentParent(
                    student: $student,
                    channels: $channels,
                    template: 'announcement',
                    event: 'announcement',
                    data: [
                        'title'        => $a->title,
                        'body_plain'   => $bodyPlain,
                        'published_at' => $publishedAt,
                        'class_name'   => $a->schoolClass?->name,
                        'url'          => $url,
                        // Used for email subject builder
                        'student_name' => $student->name,
                    ],
                    notifiable: $a,
                );
            } catch (\Throwable $e) {
                Log::warning('[NotifAnnouncement] gagal queue: ' . $e->getMessage(), [
                    'announcement_id' => $a->id,
                    'student_id'      => $student->id,
                ]);
            }
        }

        $a->forceFill(['notification_sent_at' => now()])->saveQuietly();
    }
}
