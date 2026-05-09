<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffSchedules\Tables;

use App\Models\StaffSchedule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StaffSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->formatStateUsing(fn ($state) => StaffSchedule::DAYS[$state] ?? '-')
                    ->sortable()
                    ->badge(),
                TextColumn::make('start_time')
                    ->label('Waktu')
                    ->formatStateUsing(fn ($record) => $record->time_range)
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => StaffSchedule::TYPES[$state] ?? ucfirst((string) $state)),
                TextColumn::make('staff.name')->label('Guru')->searchable()->sortable(),
                TextColumn::make('subject.name')->label('Mapel')->toggleable(),
                TextColumn::make('class_name')->label('Kelas')->toggleable(),
                TextColumn::make('location')->label('Lokasi')->toggleable(),
                TextColumn::make('academic_year')->label('TA')->toggleable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('day_of_week', 'asc')
            ->filters([
                SelectFilter::make('type')->label('Tipe')->options(StaffSchedule::TYPES),
                SelectFilter::make('day_of_week')->label('Hari')->options(StaffSchedule::DAYS),
                SelectFilter::make('staff_member_id')
                    ->label('Guru')
                    ->relationship('staff', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->options(fn () => \App\Models\StaffSchedule::query()
                        ->whereNotNull('academic_year')
                        ->distinct()
                        ->pluck('academic_year', 'academic_year')
                        ->toArray()),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
