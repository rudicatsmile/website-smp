<?php

declare(strict_types=1);

namespace App\Filament\Resources\NotificationLogs\Pages;

use App\Filament\Resources\NotificationLogs\NotificationLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewNotificationLog extends ViewRecord
{
    protected static string $resource = NotificationLogResource::class;
}
