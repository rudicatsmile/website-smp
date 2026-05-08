<?php

namespace App\Filament\Resources\PageHeroes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageHeroForm
{
    public const KEY_OPTIONS = [
        'profil' => 'Profil',
        'akademik' => 'Akademik',
        'fasilitas' => 'Fasilitas',
        'prestasi' => 'Prestasi',
        'galeri' => 'Galeri',
        'berita' => 'Berita',
        'download' => 'Download',
        'kontak' => 'Kontak',
    ];

    public const ICON_OPTIONS = [
        'building-office-2' => 'Gedung (building-office-2)',
        'academic-cap' => 'Topi Akademik (academic-cap)',
        'building-library' => 'Perpustakaan (building-library)',
        'trophy' => 'Piala (trophy)',
        'photo' => 'Foto (photo)',
        'newspaper' => 'Koran (newspaper)',
        'arrow-down-tray' => 'Unduh (arrow-down-tray)',
        'sparkles' => 'Sparkles (sparkles)',
        'book-open' => 'Buku Terbuka (book-open)',
        'users' => 'Pengguna (users)',
        'briefcase' => 'Koper (briefcase)',
        'chat-bubble-left-right' => 'Chat (chat-bubble-left-right)',
    ];

    public const COLOR_OPTIONS = [
        'emerald-600' => 'Emerald 600',
        'emerald-700' => 'Emerald 700',
        'emerald-800' => 'Emerald 800',
        'teal-600' => 'Teal 600',
        'teal-700' => 'Teal 700',
        'teal-800' => 'Teal 800',
        'sky-600' => 'Sky 600',
        'sky-700' => 'Sky 700',
        'sky-800' => 'Sky 800',
        'indigo-600' => 'Indigo 600',
        'indigo-700' => 'Indigo 700',
        'indigo-800' => 'Indigo 800',
        'purple-600' => 'Purple 600',
        'purple-700' => 'Purple 700',
        'purple-800' => 'Purple 800',
        'rose-600' => 'Rose 600',
        'rose-700' => 'Rose 700',
        'rose-800' => 'Rose 800',
        'amber-500' => 'Amber 500',
        'amber-600' => 'Amber 600',
        'amber-700' => 'Amber 700',
        'orange-600' => 'Orange 600',
        'orange-700' => 'Orange 700',
        'slate-700' => 'Slate 700',
        'slate-800' => 'Slate 800',
        'slate-900' => 'Slate 900',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Halaman')
                    ->columns(2)
                    ->schema([
                        Select::make('key')
                            ->label('Halaman')
                            ->options(self::KEY_OPTIONS)
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit')
                            ->helperText('Identifier halaman. Tidak dapat diubah setelah dibuat.'),
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(150),
                        Textarea::make('subtitle')
                            ->label('Subjudul / Deskripsi')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),

                Section::make('Tampilan')
                    ->columns(2)
                    ->schema([
                        Select::make('icon')
                            ->label('Icon')
                            ->options(self::ICON_OPTIONS)
                            ->searchable()
                            ->nullable()
                            ->helperText('Icon dari Heroicons (outline).'),
                        FileUpload::make('background_image')
                            ->label('Background Image (opsional)')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('page-heroes')
                            ->visibility('public')
                            ->maxSize(3072)
                            ->helperText('Akan ditampilkan di belakang gradient overlay.'),
                    ]),

                Section::make('Gradient Overlay')
                    ->description('Warna gradient yang dilapiskan di atas hero. Jika ada background image, gunakan opacity untuk transparansi.')
                    ->columns(4)
                    ->schema([
                        Select::make('overlay_from')
                            ->label('Warna Awal')
                            ->options(self::COLOR_OPTIONS)
                            ->searchable()
                            ->required()
                            ->default('emerald-600'),
                        Select::make('overlay_via')
                            ->label('Warna Tengah (opsional)')
                            ->options(self::COLOR_OPTIONS)
                            ->searchable()
                            ->nullable()
                            ->default('emerald-700'),
                        Select::make('overlay_to')
                            ->label('Warna Akhir')
                            ->options(self::COLOR_OPTIONS)
                            ->searchable()
                            ->required()
                            ->default('teal-800'),
                        TextInput::make('overlay_opacity')
                            ->label('Opacity (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(100)
                            ->required(),
                    ]),

                Section::make('Pengaturan')
                    ->columns(2)
                    ->schema([
                        Toggle::make('show_breadcrumb')
                            ->label('Tampilkan Breadcrumb')
                            ->default(true),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
