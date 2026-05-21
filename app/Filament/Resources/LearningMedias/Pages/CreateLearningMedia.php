<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMedias\Pages;

use App\Filament\Resources\LearningMedias\LearningMediaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningMedia extends CreateRecord
{
    protected static string $resource = LearningMediaResource::class;
}
