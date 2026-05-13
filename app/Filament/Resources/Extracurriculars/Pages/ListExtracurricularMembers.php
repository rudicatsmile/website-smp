<?php

declare(strict_types=1);

namespace App\Filament\Resources\Extracurriculars\Pages;

use App\Filament\Resources\Extracurriculars\ExtracurricularMemberResource;
use App\Models\ExtracurricularMember;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListExtracurricularMembers extends ListRecords
{
    protected static string $resource = ExtracurricularMemberResource::class;

    public function getTabs(): array
    {
        return [
            'pending' => Tab::make('Menunggu')
                ->badge(ExtracurricularMember::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),

            'approved' => Tab::make('Diterima')
                ->badge(ExtracurricularMember::where('status', 'approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved')),

            'rejected' => Tab::make('Ditolak')
                ->badge(ExtracurricularMember::where('status', 'rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected')),

            'all' => Tab::make('Semua'),
        ];
    }
}
