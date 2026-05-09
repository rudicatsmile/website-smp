<?php

declare(strict_types=1);

namespace App\Filament\Resources\InternalAnnouncements\Pages;

use App\Filament\Resources\InternalAnnouncements\InternalAnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInternalAnnouncement extends CreateRecord
{
    protected static string $resource = InternalAnnouncementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] ??= auth()->id();

        return $data;
    }
}
