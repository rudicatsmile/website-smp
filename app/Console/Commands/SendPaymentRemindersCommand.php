<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\StudentPayment;
use App\Services\Notifications\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPaymentRemindersCommand extends Command
{
    protected $signature = 'notifications:payment-reminders
                            {--dry-run : Tampilkan tagihan yang akan dikirim tanpa benar-benar mengirim}';

    protected $description = 'Kirim pengingat tagihan pembayaran (H-3, H-1, jatuh tempo, overdue) ke orang tua via WA/Email';

    public function handle(NotificationService $service): int
    {
        $config = config('notifications.events.payment_due');
        if (empty($config['enabled'])) {
            $this->warn('Reminder pembayaran dinonaktifkan via config (NOTIF_PAYMENT_ENABLED).');
            return self::SUCCESS;
        }

        $offsets = $config['reminder_days'] ?? [3, 1, 0, -3];
        $channels = $config['channels'] ?? ['whatsapp', 'email'];
        $today = Carbon::today();
        $dryRun = (bool) $this->option('dry-run');

        $totalSent = 0;
        $totalSkipped = 0;

        foreach ($offsets as $offset) {
            $targetDate = $today->copy()->addDays($offset);
            $isOverdue = $offset < 0;

            $query = StudentPayment::query()
                ->whereIn('status', $isOverdue ? ['unpaid', 'overdue'] : ['unpaid'])
                ->whereDate('due_date', $targetDate)
                ->with('student.schoolClass');

            $payments = $query->get();
            if ($payments->isEmpty()) {
                continue;
            }

            $this->line("→ Offset {$offset} hari ({$targetDate->toDateString()}): {$payments->count()} tagihan");

            foreach ($payments as $payment) {
                $student = $payment->student;
                if (! $student) {
                    $totalSkipped++;
                    continue;
                }
                if (! $student->parent_phone && ! $student->parent_email) {
                    $totalSkipped++;
                    continue;
                }

                if ($dryRun) {
                    $this->line("   [dry] {$student->name} - {$payment->type_label} {$payment->period} ({$payment->amount_formatted})");
                    continue;
                }

                $service->notifyStudentParent(
                    student: $student,
                    channels: $channels,
                    template: 'payment-due',
                    event: 'payment_due',
                    data: [
                        'parent_name'      => $student->parent_name,
                        'student_name'     => $student->name,
                        'nis'              => $student->nis,
                        'class_name'       => $student->schoolClass?->name,
                        'type_label'       => $payment->type_label,
                        'period'           => $payment->period,
                        'amount_formatted' => $payment->amount_formatted,
                        'due_date'         => optional($payment->due_date)->translatedFormat('l, d F Y'),
                        'is_overdue'       => $isOverdue,
                        'days_overdue'     => $isOverdue ? abs($offset) : 0,
                        'days_to_due'      => $offset,
                    ],
                    notifiable: $payment,
                );
                $totalSent++;
            }
        }

        $this->info("Selesai. Antrian: {$totalSent} | Dilewati: {$totalSkipped}" . ($dryRun ? ' (DRY RUN)' : ''));
        return self::SUCCESS;
    }
}
