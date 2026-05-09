<?php

declare(strict_types=1);

namespace App\Filament\Resources\CounselingTickets\Pages;

use App\Filament\Resources\CounselingTickets\CounselingTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCounselingTicket extends EditRecord
{
    protected static string $resource = CounselingTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
