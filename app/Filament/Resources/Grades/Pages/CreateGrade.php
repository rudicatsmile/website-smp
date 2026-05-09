<?php

declare(strict_types=1);

namespace App\Filament\Resources\Grades\Pages;

use App\Filament\Resources\Grades\GradeResource;
use App\Models\Grade;
use Filament\Resources\Pages\CreateRecord;

class CreateGrade extends CreateRecord
{
    protected static string $resource = GradeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['predikat']) && ! empty($data['nilai_akhir'])) {
            $data['predikat'] = Grade::calcPredikat((float) $data['nilai_akhir']);
        }
        return $data;
    }
}
