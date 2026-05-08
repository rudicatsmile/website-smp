<?php

namespace App\Filament\Resources\Popups\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PopupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Popup')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Isi')
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Gambar (opsional)')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('popups')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        TextInput::make('link_url')
                            ->label('URL Tombol Aksi')
                            ->placeholder('/spmb atau https://...')
                            ->helperText('Boleh path relatif (contoh: /spmb) atau URL lengkap (https://...).')
                            ->maxLength(500),
                        TextInput::make('link_text')
                            ->label('Teks Tombol Aksi')
                            ->placeholder('Selengkapnya'),
                    ]),
                Section::make('Tampilan')
                    ->columns(3)
                    ->schema([
                        Select::make('size')
                            ->label('Ukuran')
                            ->options(['sm' => 'Kecil', 'md' => 'Sedang', 'lg' => 'Besar', 'xl' => 'Sangat Besar'])
                            ->default('md')
                            ->required(),
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('show_once')
                            ->label('Tampilkan sekali per pengunjung')
                            ->helperText('Jika aktif, popup hanya muncul saat kunjungan pertama (sampai pengguna hapus cookie/localStorage).')
                            ->default(true),
                    ]),
                Section::make('Penjadwalan')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                        DateTimePicker::make('starts_at')
                            ->label('Mulai Tampil')
                            ->helperText('Kosongkan untuk segera aktif.'),
                        DateTimePicker::make('ends_at')
                            ->label('Berhenti Tampil')
                            ->helperText('Kosongkan untuk tanpa batas akhir.')
                            ->after('starts_at'),
                    ]),
            ]);
    }
}
