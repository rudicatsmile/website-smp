<?php

declare(strict_types=1);

namespace App\Filament\Resources\CounselingTickets\Pages;

use App\Filament\Resources\CounselingTickets\CounselingTicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCounselingTicket extends CreateRecord
{
    protected static string $resource = CounselingTicketResource::class;
}
