<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans\Pages;

use App\Filament\Resources\CurriculumPlans\CurriculumPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCurriculumPlan extends CreateRecord
{
    protected static string $resource = CurriculumPlanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
}
