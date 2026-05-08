<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgramsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Program')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Program')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', \Illuminate\Support\Str::slug($state));
                                    }),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('URL slug untuk halaman detail'),
                            ]),
                        TextInput::make('icon')
                            ->label('Icon (Heroicon)')
                            ->placeholder('sparkles, star, trophy, dll')
                            ->helperText('Nama icon dari Heroicon set'),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->helperText('Ringkasan singkat yang ditampilkan di kartu'),
                        RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Tampilan')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->directory('programs')
                            ->disk('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('button_text')
                                    ->label('Teks Tombol')
                                    ->default('Lihat Detail')
                                    ->maxLength(50),
                                TextInput::make('button_link')
                                    ->label('Link Tombol')
                                    ->placeholder('https://... atau biarkan kosong untuk link ke detail')
                                    ->helperText('Kosongkan untuk link otomatis ke halaman detail'),
                            ]),
                    ])
                    ->columns(2),
                Section::make('Pengaturan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Urutan tampil (terkecil di atas)'),
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->inline(false),
                                Toggle::make('is_featured')
                                    ->label('Unggulan')
                                    ->default(false)
                                    ->helperText('Tampilkan di halaman beranda')
                                    ->inline(false),
                            ]),
                    ])
                    ->columns(3),
            ]);
    }
}
