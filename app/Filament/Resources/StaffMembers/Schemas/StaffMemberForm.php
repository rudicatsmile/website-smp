<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffMembers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StaffMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('nip')
                            ->label('NIP')
                            ->maxLength(50),
                        TextInput::make('nuptk')
                            ->label('NUPTK')
                            ->maxLength(50),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ]),
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir')
                            ->maxLength(100),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->native(false),
                    ]),

                Section::make('Jabatan')
                    ->columns(2)
                    ->schema([
                        Select::make('staff_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('position')
                            ->label('Jabatan')
                            ->maxLength(255),
                        Toggle::make('is_principal')
                            ->label('Kepala Sekolah')
                            ->default(false),
                        DatePicker::make('joined_at')
                            ->label('Tanggal Bergabung')
                            ->native(false),
                        TextInput::make('years_of_service')
                            ->label('Masa Kerja (Tahun)')
                            ->numeric()
                            ->minValue(0),
                    ]),

                Section::make('Akademik')
                    ->schema([
                        Repeater::make('subjects')
                            ->label('Mata Pelajaran')
                            ->schema([
                                TextInput::make('subject')
                                    ->label('Mata Pelajaran')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->itemLabel(fn (array $state): ?string => $state['subject'] ?? null)
                            ->default([]),
                        Repeater::make('education')
                            ->label('Pendidikan')
                            ->schema([
                                TextInput::make('degree')
                                    ->label('Gelar')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('major')
                                    ->label('Jurusan')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('institution')
                                    ->label('Institusi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('year')
                                    ->label('Tahun Lulus')
                                    ->maxLength(10),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['degree'] . ' - ' . ($state['institution'] ?? '') ?? null)
                            ->default([]),
                        Repeater::make('certifications')
                            ->label('Sertifikasi')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Sertifikasi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('issuer')
                                    ->label('Penerbit')
                                    ->maxLength(255),
                                TextInput::make('year')
                                    ->label('Tahun')
                                    ->maxLength(10),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->default([]),
                        Repeater::make('experiences')
                            ->label('Pengalaman Kerja')
                            ->schema([
                                TextInput::make('position')
                                    ->label('Posisi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('organization')
                                    ->label('Organisasi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('start_year')
                                    ->label('Tahun Mulai')
                                    ->maxLength(10),
                                TextInput::make('end_year')
                                    ->label('Tahun Selesai')
                                    ->maxLength(10),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['position'] . ' - ' . ($state['organization'] ?? '') ?? null)
                            ->default([]),
                    ]),

                Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->maxLength(50),
                        Repeater::make('social')
                            ->label('Media Sosial')
                            ->schema([
                                TextInput::make('platform')
                                    ->label('Platform')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(500),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['platform'] ?? null)
                            ->default([]),
                    ]),

                Section::make('Konten')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('staff-photos')
                            ->visibility('public')
                            ->maxSize(2048),
                        Textarea::make('bio')
                            ->label('Biografi')
                            ->rows(4),
                        TextInput::make('quote')
                            ->label('Kutipan')
                            ->maxLength(500),
                    ]),

                Section::make('Pengaturan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
