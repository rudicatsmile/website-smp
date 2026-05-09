<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffMembers\Pages;

use App\Filament\Resources\StaffMembers\StaffMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaffMember extends EditRecord
{
    protected static string $resource = StaffMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
