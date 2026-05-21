<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMethods\Pages;

use App\Filament\Resources\LearningMethods\LearningMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningMethod extends CreateRecord
{
    protected static string $resource = LearningMethodResource::class;
}
