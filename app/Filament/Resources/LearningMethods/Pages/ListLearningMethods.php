<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMethods\Pages;

use App\Filament\Resources\LearningMethods\LearningMethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLearningMethods extends ListRecords
{
    protected static string $resource = LearningMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
