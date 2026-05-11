<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Models\ParentNote;
use App\Models\ParentNoteMessage;
use Illuminate\Support\Facades\Log;

class ParentNoteNotifier
{
    public function __construct(protected NotificationService $notifier) {}

    /**
     * Dispatch WA + Email notification for a newly created parent_note message.
     * - When sender is teacher -> notify parents (via student.parent_phone/email)
     * - When sender is parent -> notify homeroom teacher (StaffMember.phone/email)
     * - Internal notes are skipped.
     */
    public function dispatchForMessage(ParentNoteMessage $message): void
    {
        if ($message->is_internal) {
            return;
        }

        $config = config('notifications.events.parent_note');
        if (empty($config['enabled'])) {
            return;
        }

        /** @var ParentNote|null $note */
        $note = $message->note()->with(['student', 'schoolClass', 'homeroomTeacher'])->first();
        if (! $note || ! $note->student) {
            return;
        }

        $student = $note->student;
        $channels = $config['channels'] ?? ['whatsapp', 'email'];

        $baseData = [
            'student_name'   => $student->name,
            'class_name'     => $note->schoolClass?->name ?? $student->schoolClass?->name,
            'subject'        => $note->subject,
            'category_label' => $note->category_label,
            'code'           => $note->code,
            'body'           => $message->body,
            'url'            => $this->parentUrlFor($note),
        ];

        try {
            if ($message->sender_type === 'teacher') {
                $this->notifier->notifyStudentParent(
                    student: $student,
                    channels: $channels,
                    template: 'parent-note',
                    event: 'parent_note',
                    data: array_merge($baseData, [
                        'recipient_name' => $student->parent_name,
                        'recipient_type' => 'parent',
                    ]),
                    notifiable: $note,
                    triggeredBy: $message->user_id,
                );
            } elseif ($message->sender_type === 'parent') {
                $teacher = $note->homeroomTeacher;
                if ($teacher) {
                    $phone = $teacher->whatsapp ?: $teacher->phone;
                    $email = $teacher->email;
                    if ($phone || $email) {
                        $this->notifier->sendCustom(
                            recipient: [
                                'name'  => $teacher->name,
                                'phone' => $phone,
                                'email' => $email,
                            ],
                            channels: $channels,
                            subject: app(MessageBuilder::class)->subject('parent_note', $baseData),
                            body: app(MessageBuilder::class)->render('parent-note', array_merge($baseData, [
                                'recipient_name' => $teacher->name,
                                'recipient_type' => 'teacher',
                            ])),
                            event: 'parent_note',
                            notifiable: $note,
                            triggeredBy: $message->user_id,
                        );
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[ParentNoteNotifier] gagal queue notif: ' . $e->getMessage(), [
                'message_id' => $message->id,
                'note_id'    => $note->id,
            ]);
        }
    }

    protected function parentUrlFor(ParentNote $note): string
    {
        try {
            return route('portal.parent.notes.show', $note->id);
        } catch (\Throwable) {
            return (string) config('app.url');
        }
    }
}
