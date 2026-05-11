<?php

namespace App\Filament\Resources\Achievements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AchievementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Prestasi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Prestasi')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state)))
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        TextInput::make('institution')
                            ->label('Lembaga / Sekolah')
                            ->placeholder('Contoh: SMP Al Wathoniyah 9'),
                        TextInput::make('level')
                            ->label('Tingkat')
                            ->placeholder('Kecamatan / Kota / Provinsi / Nasional'),
                        TextInput::make('rank')
                            ->label('Peringkat / Capaian')
                            ->placeholder('Juara 1, Harapan 2, 10 Besar, dst.'),
                        DatePicker::make('achieved_at')
                            ->label('Tanggal Diraih')
                            ->native(false),
                        FileUpload::make('image')
                            ->label('Poster / Foto')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('achievements')
                            ->visibility('public')
                            ->maxSize(3072)
                            ->columnSpanFull(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Tampil di kartu beranda & daftar prestasi.')
                            ->columnSpanFull(),
                        RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->columnSpanFull(),
                    ]),
                Section::make('Pengaturan Tampilan')
                    ->columns(3)
                    ->schema([
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Tampilkan di Beranda')
                            ->default(true),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
