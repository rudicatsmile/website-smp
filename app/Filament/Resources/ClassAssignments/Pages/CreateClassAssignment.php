<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassAssignments\Pages;

use App\Filament\Resources\ClassAssignments\ClassAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassAssignment extends CreateRecord
{
    protected static string $resource = ClassAssignmentResource::class;
}
