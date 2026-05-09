<?php

namespace App\Filament\Resources\SchoolEvents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SchoolEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
                DatePicker::make('event_date')
                    ->label('Tanggal')
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Waktu Mulai'),
                TimePicker::make('end_time')
                    ->label('Waktu Selesai'),
                TextInput::make('location')
                    ->label('Lokasi')
                    ->maxLength(255),
                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'umum' => 'Umum',
                        'akademik' => 'Akademik',
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'rapat-guru' => 'Rapat Guru',
                        'libur' => 'Libur',
                    ])
                    ->default('umum')
                    ->required(),
                Select::make('color')
                    ->label('Warna')
                    ->options([
                        'blue' => 'Biru',
                        'green' => 'Hijau',
                        'amber' => 'Kuning',
                        'rose' => 'Merah',
                        'purple' => 'Ungu',
                    ])
                    ->default('blue')
                    ->required(),
                Toggle::make('is_holiday')
                    ->label('Hari Libur')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ])
            ->columns(3);
    }
}
