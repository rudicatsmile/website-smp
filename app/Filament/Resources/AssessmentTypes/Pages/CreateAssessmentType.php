<?php

declare(strict_types=1);

namespace App\Filament\Resources\AssessmentTypes\Pages;

use App\Filament\Resources\AssessmentTypes\AssessmentTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssessmentType extends CreateRecord
{
    protected static string $resource = AssessmentTypeResource::class;
}
