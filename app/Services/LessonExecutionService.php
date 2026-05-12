<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LessonSession;
use App\Models\StaffMember;

class LessonExecutionService
{
    public function start(LessonSession $session, StaffMember $teacher): void
    {
        if ($session->staff_member_id !== $teacher->id) {
            throw new \RuntimeException('Guru tidak sesuai dengan jadwal sesi ini.');
        }
        if (! in_array($session->status, ['published', 'ongoing'], true)) {
            throw new \RuntimeException("Sesi berstatus '{$session->status_label}', tidak bisa dimulai.");
        }

        $session->update([
            'status' => 'ongoing',
            'actual_start_at' => now(),
        ]);
    }

    public function complete(LessonSession $session, array $data): void
    {
        if ($session->status !== 'ongoing') {
            throw new \RuntimeException("Sesi harus dalam status 'Sedang Berlangsung' untuk diselesaikan.");
        }

        $session->update([
            'status' => 'completed',
            'actual_end_at' => now(),
            'completed_at' => now(),
            'achievement_percent' => $data['achievement_percent'] ?? null,
            'execution_notes' => $data['execution_notes'] ?? null,
            'homework_notes' => $data['homework_notes'] ?? null,
            'student_work_links' => $data['student_work_links'] ?? null,
            'issues_notes' => $data['issues_notes'] ?? null,
        ]);
    }

    public function cancel(LessonSession $session, string $reason): void
    {
        if (in_array($session->status, ['completed'], true)) {
            throw new \RuntimeException('Sesi yang sudah selesai tidak bisa dibatalkan.');
        }

        $session->update([
            'status' => 'cancelled',
            'cancelled_reason' => $reason,
        ]);
    }
}
