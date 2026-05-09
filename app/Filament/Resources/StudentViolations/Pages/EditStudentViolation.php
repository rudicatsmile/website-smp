<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentViolations\Pages;

use App\Filament\Resources\StudentViolations\StudentViolationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentViolation extends EditRecord
{
    protected static string $resource = StudentViolationResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
