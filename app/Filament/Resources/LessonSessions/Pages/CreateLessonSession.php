<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\Pages;

use App\Filament\Resources\LessonSessions\LessonSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLessonSession extends CreateRecord
{
    protected static string $resource = LessonSessionResource::class;
}
