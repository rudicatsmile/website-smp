<?php

declare(strict_types=1);

namespace App\Filament\Resources\InternalAnnouncements\Pages;

use App\Filament\Resources\InternalAnnouncements\InternalAnnouncementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInternalAnnouncement extends EditRecord
{
    protected static string $resource = InternalAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->visible(fn () => auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false),
        ];
    }
}
