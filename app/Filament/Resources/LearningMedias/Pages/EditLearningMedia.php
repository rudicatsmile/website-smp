<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMedias\Pages;

use App\Filament\Resources\LearningMedias\LearningMediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLearningMedia extends EditRecord
{
    protected static string $resource = LearningMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
