<?php

declare(strict_types=1);

namespace App\Filament\Resources\LearningModels\Pages;

use App\Filament\Resources\LearningModels\LearningModelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLearningModel extends EditRecord
{
    protected static string $resource = LearningModelResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
