<?php

declare(strict_types=1);

namespace App\Filament\Tahfidz\Resources\ParticipantResource\Pages;

use App\Filament\Tahfidz\Resources\ParticipantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Daftarkan Peserta')];
    }
}
