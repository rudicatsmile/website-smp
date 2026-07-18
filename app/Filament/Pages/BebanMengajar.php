<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\StaffMember;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class BebanMengajar extends Page implements HasTable
{
    use InteractsWithTable;
    use \BezhanSalleh\FilamentShield\Traits\HasPageShield;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected string $view = 'filament.pages.beban-mengajar';
    protected static ?string $title = 'Laporan Beban Mengajar';
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    public function table(Table $table): Table
    {
        return $table
            ->query(StaffMember::query()->active()->ordered())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Guru')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('teachingSubjects.name')
                    ->label('Keahlian (Mapel Master)')
                    ->badge()
                    ->color('info')
                    ->separator(','),

                TextColumn::make('real_subjects')
                    ->label('Beban Mengajar (Rencana Pembelajaran)')
                    ->state(function (StaffMember $record) {
                        $plans = \App\Models\CurriculumPlan::where('staff_member_id', $record->id)
                            ->with(['subject', 'schoolClass'])
                            ->get();
                        
                        if ($plans->isEmpty()) {
                            return '-';
                        }
                        
                        $list = $plans->map(function ($plan) {
                            $mapel = $plan->subject?->name ?? 'Unknown';
                            $kelas = $plan->schoolClass?->name ?? 'Unknown';
                            return "{$mapel} ({$kelas})";
                        });
                        
                        return $list->implode(', ');
                    })
                    ->badge()
                    ->color('success')
                    ->separator(','),
            ])
            ->striped();
    }
}
