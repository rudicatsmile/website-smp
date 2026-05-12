<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans\Pages;

use App\Filament\Resources\CurriculumPlans\CurriculumPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCurriculumPlan extends EditRecord
{
    protected static string $resource = CurriculumPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
