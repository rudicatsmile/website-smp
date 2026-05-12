<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\LessonSession;
use App\Models\StaffMember;
use App\Services\LessonExecutionService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class TeachingToday extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string|\UnitEnum|null $navigationGroup = 'Materi Pelajaran';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Mengajar Hari Ini';

    protected static ?string $title = 'Mengajar Hari Ini';

    protected string $view = 'filament.pages.teaching-today';

    public Collection $sessions;

    public ?int $selectedSessionId = null;

    public string $date;

    public function mount(): void
    {
        $this->date = today()->toDateString();
        $this->loadSessions();
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => $this->sessions->count(),
            'published' => $this->sessions->where('status', 'published')->count(),
            'ongoing' => $this->sessions->where('status', 'ongoing')->count(),
            'completed' => $this->sessions->where('status', 'completed')->count(),
            'cancelled' => $this->sessions->where('status', 'cancelled')->count(),
        ];
    }

    public function loadSessions(): void
    {
        $user = auth()->user();
        $query = LessonSession::query()
            ->with(['schoolClass', 'subject', 'teacher', 'materials', 'assignments'])
            ->where('session_date', $this->date)
            ->orderBy('start_time');

        if (! $user?->hasAnyRole(['super_admin', 'admin'])) {
            $staff = $this->getStaff();
            if (! $staff) {
                $this->sessions = collect();
                return;
            }
            $query->where('staff_member_id', $staff->id);
        }

        $this->sessions = $query->get();
    }

    public function selectSession(int $id): void
    {
        $this->selectedSessionId = $id;
    }

    public function startSession(int $id): void
    {
        $session = LessonSession::findOrFail($id);
        $staff = $this->getStaff();
        if (! $staff) return;

        try {
            app(LessonExecutionService::class)->start($session, $staff);
            Notification::make()->title('Sesi dimulai!')->success()->send();
            $this->loadSessions();
        } catch (\RuntimeException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        }
    }

    public function completeSession(int $id, array $data): void
    {
        $session = LessonSession::findOrFail($id);
        try {
            app(LessonExecutionService::class)->complete($session, $data);
            Notification::make()->title('Sesi selesai dicatat!')->success()->send();
            $this->selectedSessionId = null;
            $this->loadSessions();
        } catch (\RuntimeException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        }
    }

    public function cancelSession(int $id, string $reason): void
    {
        $session = LessonSession::findOrFail($id);
        try {
            app(LessonExecutionService::class)->cancel($session, $reason);
            Notification::make()->title('Sesi dibatalkan')->success()->send();
            $this->loadSessions();
        } catch (\RuntimeException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        }
    }

    protected function getStaff(): ?StaffMember
    {
        return auth()->user()?->staffMember;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }
}
