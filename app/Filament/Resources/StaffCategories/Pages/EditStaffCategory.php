<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffCategories\Pages;

use App\Filament\Resources\StaffCategories\StaffCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaffCategory extends EditRecord
{
    protected static string $resource = StaffCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
