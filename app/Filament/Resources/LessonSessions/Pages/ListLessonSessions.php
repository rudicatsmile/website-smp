<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\Pages;

use App\Filament\Resources\LessonSessions\LessonSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLessonSessions extends ListRecords
{
    protected static string $resource = LessonSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
