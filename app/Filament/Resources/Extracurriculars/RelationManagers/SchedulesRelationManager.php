<?php

declare(strict_types=1);

namespace App\Filament\Resources\Extracurriculars\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Jadwal Latihan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('day_of_week')
                ->label('Hari')
                ->options([
                    1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                    4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
                ])
                ->required(),

            TimePicker::make('start_time')->label('Mulai')->required()->seconds(false),
            TimePicker::make('end_time')->label('Selesai')->required()->seconds(false),

            TextInput::make('location')->label('Lokasi')->nullable(),
            TextInput::make('notes')->label('Catatan')->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_name')->label('Hari'),
                TextColumn::make('start_time')->label('Mulai'),
                TextColumn::make('end_time')->label('Selesai'),
                TextColumn::make('location')->label('Lokasi'),
                TextColumn::make('notes')->label('Catatan')->limit(40),
            ])
            ->defaultSort('day_of_week')
            ->headerActions([CreateAction::make()->label('Tambah Jadwal')])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
