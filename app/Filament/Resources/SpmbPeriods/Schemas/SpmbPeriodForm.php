<?php

namespace App\Filament\Resources\SpmbPeriods\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SpmbPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama Periode')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->after('start_date'),
                TextInput::make('quota')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(0),
                TextInput::make('fee')
                    ->label('Biaya (Rp)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->helperText('Hanya satu periode aktif pada satu waktu disarankan.')
                    ->default(false)
                    ->required(),
            ]);
    }
}
