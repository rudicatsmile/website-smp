<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
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
                Textarea::make('message')
                    ->label('Pesan')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('link_url')
                    ->label('URL Link')
                    ->url()
                    ->placeholder('https://example.com'),
                TextInput::make('link_text')
                    ->label('Teks Link')
                    ->default('Lihat Detail')
                    ->maxLength(50),
                Select::make('color')
                    ->label('Warna')
                    ->options([
                        'emerald' => 'Hijau',
                        'blue' => 'Biru',
                        'amber' => 'Kuning',
                        'rose' => 'Merah',
                    ])
                    ->default('emerald')
                    ->required(),
                DateTimePicker::make('start_at')
                    ->label('Mulai Tampil'),
                DateTimePicker::make('end_at')
                    ->label('Selesai Tampil'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                Toggle::make('is_dismissible')
                    ->label('Bisa Ditutup')
                    ->default(true),
                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ])
            ->columns(3);
    }
}
