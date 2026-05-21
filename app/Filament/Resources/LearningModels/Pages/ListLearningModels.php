<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningModels\Pages;

use App\Filament\Resources\LearningModels\LearningModelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLearningModels extends ListRecords
{
    protected static string $resource = LearningModelResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
