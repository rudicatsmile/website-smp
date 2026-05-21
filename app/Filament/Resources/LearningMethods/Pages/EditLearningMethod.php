<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningMethods\Pages;

use App\Filament\Resources\LearningMethods\LearningMethodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLearningMethod extends EditRecord
{
    protected static string $resource = LearningMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
