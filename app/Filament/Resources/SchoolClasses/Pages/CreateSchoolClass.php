<?php

declare(strict_types=1);

namespace App\Filament\Resources\SchoolClasses\Pages;

use App\Filament\Resources\SchoolClasses\SchoolClassResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSchoolClass extends CreateRecord
{
    protected static string $resource = SchoolClassResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['name']) && isset($data['grade'], $data['section'])) {
            $data['name'] = $data['grade'] . $data['section'];
        }
        return $data;
    }
}
