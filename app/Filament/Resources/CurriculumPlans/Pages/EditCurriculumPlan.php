<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans\Pages;

use App\Filament\Resources\CurriculumPlans\CurriculumPlanResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCurriculumPlan extends EditRecord
{
    protected static string $resource = CurriculumPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Cetak Report')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('print.curriculum-plans', $this->record))
                ->openUrlInNewTab(),
            Action::make('executionSummary')
                ->label('Laporan Realisasi')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->url(fn () => route('print.curriculum-plans.execution-summary', $this->record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
