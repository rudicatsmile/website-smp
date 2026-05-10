<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\StudentAttendance;
use App\Services\Notifications\NotificationService;
use Illuminate\Support\Facades\Log;

class StudentAttendanceObserver
{
    public function __construct(protected NotificationService $service) {}

    public function created(StudentAttendance $attendance): void
    {
        $this->maybeNotify($attendance);
    }

    public function updated(StudentAttendance $attendance): void
    {
        // Only notify when status actually changes
        if ($attendance->wasChanged('status')) {
            $this->maybeNotify($attendance);
        }
    }

    protected function maybeNotify(StudentAttendance $attendance): void
    {
        // Suppression flag set by LeaveRequestService while auto-creating
        // attendance rows from an approved leave request — avoids dual notifications.
        if (app()->bound('skip_attendance_notif') && app('skip_attendance_notif') === true) {
            return;
        }

        $config = config('notifications.events.absensi');
        if (empty($config['enabled'])) {
            return;
        }

        $notifyStatuses = $config['statuses'] ?? ['alpa', 'sakit', 'izin', 'terlambat'];
        if (! in_array($attendance->status, $notifyStatuses, true)) {
            return;
        }

        $student = $attendance->student;
        if (! $student) {
            return;
        }

        try {
            $this->service->notifyStudentParent(
                student: $student,
                channels: $config['channels'] ?? ['whatsapp', 'email'],
                template: 'absensi',
                event: 'absensi',
                data: [
                    'parent_name'  => $student->parent_name,
                    'student_name' => $student->name,
                    'nis'          => $student->nis,
                    'class_name'   => $student->schoolClass?->name,
                    'date'         => optional($attendance->date)->translatedFormat('l, d F Y'),
                    'status_label' => StudentAttendance::STATUSES[$attendance->status] ?? ucfirst((string) $attendance->status),
                    'note'         => $attendance->note,
                ],
                notifiable: $attendance,
                triggeredBy: auth()->id(),
            );
        } catch (\Throwable $e) {
            Log::warning('[NotifAbsensi] gagal queue: ' . $e->getMessage(), [
                'attendance_id' => $attendance->id,
            ]);
        }
    }
}
