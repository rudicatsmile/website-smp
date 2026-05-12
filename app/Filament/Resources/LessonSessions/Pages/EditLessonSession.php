<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\Pages;

use App\Filament\Resources\LessonSessions\LessonSessionResource;
use App\Models\LessonSession;
use App\Services\LessonExecutionService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLessonSession extends EditRecord
{
    protected static string $resource = LessonSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('startTeaching')
                ->label('Mulai Mengajar')
                ->icon('heroicon-o-play')
                ->color('warning')
                ->visible(fn (LessonSession $record) => auth()->user()?->hasRole('teacher') && in_array($record->status, ['published', 'ongoing']))
                ->action(function (LessonSession $record) {
                    $staff = auth()->user()?->staffMember;
                    if (! $staff) {
                        Notification::make()->title('Akun tidak terhubung ke data guru')->danger()->send();
                        return;
                    }
                    try {
                        app(LessonExecutionService::class)->start($record, $staff);
                        Notification::make()->title('Sesi dimulai')->success()->send();
                        $this->refreshFormData(['status']);
                    } catch (\RuntimeException $e) {
                        Notification::make()->title($e->getMessage())->danger()->send();
                    }
                }),
            Action::make('completeTeaching')
                ->label('Selesai & Catat')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (LessonSession $record) => auth()->user()?->hasRole('teacher') && $record->status === 'ongoing')
                ->schema([
                    TextInput::make('achievement_percent')->label('Pencapaian (%)')->numeric()->minValue(0)->maxValue(100)->required(),
                    Textarea::make('execution_notes')->label('Catatan Kegiatan')->rows(3),
                    Textarea::make('homework_notes')->label('Tugas / PR')->rows(2),
                    Textarea::make('issues_notes')->label('Kendala')->rows(2),
                ])
                ->action(function (LessonSession $record, array $data) {
                    try {
                        app(LessonExecutionService::class)->complete($record, $data);
                        Notification::make()->title('Sesi selesai dicatat')->success()->send();
                        $this->refreshFormData(['status', 'achievement_percent', 'execution_notes']);
                    } catch (\RuntimeException $e) {
                        Notification::make()->title($e->getMessage())->danger()->send();
                    }
                }),
            Action::make('cancelSession')
                ->label('Batalkan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (LessonSession $record) => auth()->user()?->hasRole('teacher') && ! in_array($record->status, ['completed']))
                ->schema([
                    TextInput::make('reason')->label('Alasan Pembatalan')->required()->maxLength(255),
                ])
                ->action(function (LessonSession $record, array $data) {
                    try {
                        app(LessonExecutionService::class)->cancel($record, $data['reason']);
                        Notification::make()->title('Sesi dibatalkan')->success()->send();
                        $this->refreshFormData(['status', 'cancelled_reason']);
                    } catch (\RuntimeException $e) {
                        Notification::make()->title($e->getMessage())->danger()->send();
                    }
                }),
            DeleteAction::make(),
        ];
    }
}
