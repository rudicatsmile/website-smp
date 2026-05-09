<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassAnnouncements\Pages;

use App\Filament\Resources\ClassAnnouncements\ClassAnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassAnnouncement extends CreateRecord
{
    protected static string $resource = ClassAnnouncementResource::class;
}
