<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningObjectives\Pages;

use App\Filament\Resources\LearningObjectives\LearningObjectiveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningObjective extends CreateRecord
{
    protected static string $resource = LearningObjectiveResource::class;
}
