<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMedias\Pages;

use App\Filament\Resources\LearningMedias\LearningMediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLearningMedias extends ListRecords
{
    protected static string $resource = LearningMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
