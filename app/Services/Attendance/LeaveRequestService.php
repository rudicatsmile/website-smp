<?php

declare(strict_types=1);

namespace App\Services\Attendance;

use App\Models\LeaveRequest;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\User;
use App\Services\Notifications\NotificationService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveRequestService
{
    public function __construct(protected NotificationService $notifier) {}

    /**
     * Map leave-request type to attendance status.
     */
    public function mapTypeToStatus(string $type): string
    {
        return match ($type) {
            'sakit' => 'sakit',
            default => 'izin', // izin & dinas → izin
        };
    }

    /**
     * Check if a leave request overlaps with existing pending/approved
     * leave requests for the same student.
     */
    public function hasOverlap(int $studentId, Carbon $from, Carbon $to, ?int $ignoreId = null): bool
    {
        return LeaveRequest::query()
            ->where('student_id', $studentId)
            ->whereIn('status', ['pending', 'approved'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('date_from', '<=', $to->toDateString())
            ->where('date_to', '>=', $from->toDateString())
            ->exists();
    }

    public function approve(LeaveRequest $request, ?User $reviewer = null, ?string $note = null): LeaveRequest
    {
        return DB::transaction(function () use ($request, $reviewer, $note) {
            $request->status = 'approved';
            $request->reviewed_by = $reviewer?->id;
            $request->reviewed_at = now();
            if ($note !== null && $note !== '') {
                $request->review_note = $note;
            }
            $request->save();

            $this->writeAttendanceRows($request, $reviewer);
            $this->sendDecisionNotification($request);

            return $request->fresh();
        });
    }

    public function reject(LeaveRequest $request, ?User $reviewer = null, ?string $note = null): LeaveRequest
    {
        $wasApproved = $request->status === 'approved';

        return DB::transaction(function () use ($request, $reviewer, $note, $wasApproved) {
            $request->status = 'rejected';
            $request->reviewed_by = $reviewer?->id;
            $request->reviewed_at = now();
            if ($note !== null && $note !== '') {
                $request->review_note = $note;
            }
            $request->save();

            // If previously approved, remove attendance rows we wrote.
            if ($wasApproved) {
                $this->removeAttendanceRows($request);
            }

            $this->sendDecisionNotification($request);

            return $request->fresh();
        });
    }

    /**
     * Write StudentAttendance rows for the date range, suppressing the
     * absensi notification observer (we'll send a leave-request notif instead).
     */
    protected function writeAttendanceRows(LeaveRequest $request, ?User $reviewer): void
    {
        $skipWeekends = (bool) config('notifications.events.leave_request.skip_weekends', true);
        $status = $this->mapTypeToStatus($request->type);
        $staffId = $reviewer?->staffMember?->id;
        $tag = $request->note_tag;
        $note = $tag . ': ' . $request->reason;
        if ($request->type === 'dinas') {
            $note = $tag . ' (Dinas): ' . $request->reason;
        }

        app()->instance('skip_attendance_notif', true);
        try {
            $period = CarbonPeriod::create($request->date_from, $request->date_to);
            foreach ($period as $date) {
                /** @var Carbon $date */
                if ($skipWeekends && in_array($date->dayOfWeek, [0, 6], true)) {
                    continue;
                }
                StudentAttendance::updateOrCreate(
                    ['student_id' => $request->student_id, 'date' => $date->toDateString()],
                    [
                        'status'          => $status,
                        'staff_member_id' => $staffId,
                        'note'            => $note,
                    ],
                );
            }
        } finally {
            app()->forgetInstance('skip_attendance_notif');
        }
    }

    /**
     * Remove attendance rows previously written by this leave request
     * (identified by the note tag).
     */
    protected function removeAttendanceRows(LeaveRequest $request): void
    {
        app()->instance('skip_attendance_notif', true);
        try {
            StudentAttendance::query()
                ->where('student_id', $request->student_id)
                ->whereBetween('date', [$request->date_from, $request->date_to])
                ->where('note', 'like', $request->note_tag . '%')
                ->delete();
        } finally {
            app()->forgetInstance('skip_attendance_notif');
        }
    }

    protected function sendDecisionNotification(LeaveRequest $request): void
    {
        $config = config('notifications.events.leave_request');
        if (empty($config['enabled'])) {
            return;
        }

        /** @var Student|null $student */
        $student = $request->student;
        if (! $student) {
            return;
        }

        try {
            $this->notifier->notifyStudentParent(
                student: $student,
                channels: $config['channels'] ?? ['whatsapp', 'email'],
                template: 'leave-request',
                event: 'leave_request',
                data: [
                    'parent_name'   => $student->parent_name ?: $request->submitter_name,
                    'student_name'  => $student->name,
                    'class_name'    => $student->schoolClass?->name,
                    'type_label'    => $request->type_label,
                    'date_range'    => $request->date_range_label,
                    'day_count'     => $request->day_count,
                    'reason'        => $request->reason,
                    'status'        => $request->status,
                    'status_label'  => $request->status_label,
                    'review_note'   => $request->review_note,
                    'request_id'    => $request->id,
                ],
                notifiable: $request,
                triggeredBy: $request->reviewed_by,
            );

            $request->forceFill(['notification_sent_at' => now()])->save();
        } catch (\Throwable $e) {
            Log::warning('[NotifLeaveRequest] gagal queue: ' . $e->getMessage(), [
                'request_id' => $request->id,
            ]);
        }
    }
}
