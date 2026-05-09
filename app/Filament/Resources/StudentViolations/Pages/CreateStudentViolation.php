<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentViolations\Pages;

use App\Filament\Resources\StudentViolations\StudentViolationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentViolation extends CreateRecord
{
    protected static string $resource = StudentViolationResource::class;
}
