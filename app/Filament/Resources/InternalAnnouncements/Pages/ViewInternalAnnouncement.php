<?php

declare(strict_types=1);

namespace App\Filament\Resources\InternalAnnouncements\Pages;

use App\Filament\Resources\InternalAnnouncements\InternalAnnouncementResource;
use App\Models\InternalAnnouncementAcknowledgement;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ViewInternalAnnouncement extends Page
{
    protected static string $resource = InternalAnnouncementResource::class;

    protected string $view = 'filament.resources.internal-announcements.view';

    public $record;

    public function mount(int|string $record): void
    {
        $this->record = InternalAnnouncementResource::getEloquentQuery()->findOrFail($record);
    }

    public function getTitle(): string
    {
        return $this->record?->title ?? 'Pengumuman';
    }

    protected function getHeaderActions(): array
    {
        $actions = [];
        $user = auth()->user();

        if ($user && $this->record && ! $this->record->isAcknowledgedBy($user)) {
            $actions[] = Action::make('acknowledge')
                ->label('Tandai Sudah Dibaca')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () use ($user) {
                    InternalAnnouncementAcknowledgement::firstOrCreate(
                        ['internal_announcement_id' => $this->record->id, 'user_id' => $user->id],
                        ['acknowledged_at' => now()],
                    );
                    $this->record->refresh();
                    Notification::make()->title('Pengumuman ditandai sudah dibaca')->success()->send();
                });
        }

        if ($user?->hasAnyRole(['super_admin', 'admin', 'editor'])) {
            $actions[] = EditAction::make()->record($this->record);
        }

        return $actions;
    }
}
