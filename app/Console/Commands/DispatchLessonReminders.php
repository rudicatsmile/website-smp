<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Notifications\LessonReminderNotifier;
use Illuminate\Console\Command;

class DispatchLessonReminders extends Command
{
    protected $signature = 'lessons:dispatch-reminders';
    protected $description = 'Kirim notifikasi WA/Email untuk sesi mengajar yang akan dimulai';

    public function handle(LessonReminderNotifier $notifier): int
    {
        $count = $notifier->dispatchUpcoming();
        $this->info("{$count} reminder terkirim.");
        return self::SUCCESS;
    }
}
