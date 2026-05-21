<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningModels\Pages;

use App\Filament\Resources\LearningModels\LearningModelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningModel extends CreateRecord
{
    protected static string $resource = LearningModelResource::class;
}
