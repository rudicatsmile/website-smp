<?php

declare(strict_types=1);

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->options([
                        'ppdb' => 'PPDB',
                        'biaya' => 'Biaya',
                        'fasilitas' => 'Fasilitas',
                        'akademik' => 'Akademik',
                        'umum' => 'Umum',
                    ])
                    ->required(),
                TextInput::make('question')
                    ->label('Pertanyaan')
                    ->required()
                    ->maxLength(255),
                Textarea::make('answer')
                    ->label('Jawaban')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
                TextInput::make('keywords')
                    ->label('Kata Kunci')
                    ->helperText('Pisahkan dengan koma, contoh: spp, biaya, bayar, uang sekolah')
                    ->maxLength(500),
                TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
