<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans\Pages;

use App\Filament\Resources\CurriculumPlans\CurriculumPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCurriculumPlans extends ListRecords
{
    protected static string $resource = CurriculumPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
