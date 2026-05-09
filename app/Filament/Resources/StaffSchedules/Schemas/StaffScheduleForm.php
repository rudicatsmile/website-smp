<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffSchedules\Schemas;

use App\Models\StaffSchedule;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Jadwal')
                    ->columns(2)
                    ->schema([
                        Select::make('type')
                            ->label('Tipe')
                            ->options(StaffSchedule::TYPES)
                            ->default('mengajar')
                            ->live()
                            ->required(),
                        Select::make('staff_member_id')
                            ->label('Guru')
                            ->relationship('staff', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('material_category_id')
                            ->label('Mata Pelajaran')
                            ->relationship('subject', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn ($get) => $get('type') === 'mengajar'),
                        TextInput::make('class_name')
                            ->label('Kelas (mis. 7A)')
                            ->maxLength(20),
                        TextInput::make('location')
                            ->label('Lokasi/Ruang')
                            ->maxLength(100),
                    ]),

                Section::make('Waktu')
                    ->columns(3)
                    ->schema([
                        Select::make('day_of_week')
                            ->label('Hari')
                            ->options(StaffSchedule::DAYS)
                            ->required(),
                        TimePicker::make('start_time')
                            ->label('Jam Mulai')
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('end_time')
                            ->label('Jam Selesai')
                            ->seconds(false)
                            ->required(),
                        TextInput::make('period')
                            ->label('Periode (mis. Jam ke-1)')
                            ->maxLength(50),
                    ]),

                Section::make('Periode Berlaku')
                    ->columns(2)
                    ->schema([
                        TextInput::make('academic_year')
                            ->label('Tahun Ajaran (mis. 2025/2026)')
                            ->maxLength(20),
                        Select::make('semester')
                            ->options(['1' => 'Semester 1', '2' => 'Semester 2'])
                            ->nullable(),
                        DatePicker::make('effective_from')->label('Berlaku Dari')->native(false),
                        DatePicker::make('effective_until')->label('Berlaku Sampai')->native(false),
                    ]),

                Section::make('Tampilan')
                    ->columns(3)
                    ->schema([
                        TextInput::make('color')
                            ->label('Warna (Tailwind)')
                            ->default('emerald')
                            ->maxLength(30),
                        TextInput::make('order')->label('Urutan')->numeric()->default(0),
                        Toggle::make('is_active')->label('Aktif')->default(true),
                        Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
                    ]),
            ]);
    }
}
