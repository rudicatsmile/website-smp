<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffMembers\Pages;

use App\Filament\Resources\StaffMembers\StaffMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaffMembers extends ListRecords
{
    protected static string $resource = StaffMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_pdf')
                ->label('Cetak Laporan PDF')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn () => route('staff-members.pdf'))
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }
}
