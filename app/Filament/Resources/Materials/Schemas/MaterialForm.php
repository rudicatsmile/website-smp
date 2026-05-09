<?php

declare(strict_types=1);

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Materi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state)))
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Select::make('material_category_id')
                            ->label('Mata Pelajaran')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('staff_member_id')
                            ->label('Penulis (Guru)')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),

                Section::make('Klasifikasi')
                    ->columns(3)
                    ->schema([
                        Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'modul_ajar' => 'Modul Ajar',
                                'rpp' => 'RPP',
                                'lkpd' => 'LKPD',
                                'bahan_ajar' => 'Bahan Ajar',
                                'atp' => 'ATP',
                                'cp' => 'Capaian Pembelajaran',
                                'silabus' => 'Silabus',
                                'lainnya' => 'Lainnya',
                            ])
                            ->default('modul_ajar')
                            ->required(),
                        Select::make('grade')
                            ->label('Kelas')
                            ->options([
                                '7' => 'Kelas 7',
                                '8' => 'Kelas 8',
                                '9' => 'Kelas 9',
                                'umum' => 'Umum',
                            ])
                            ->default('umum')
                            ->required(),
                        Select::make('curriculum')
                            ->label('Kurikulum')
                            ->options([
                                'merdeka' => 'Kurikulum Merdeka',
                                'k13' => 'Kurikulum 2013',
                                'lainnya' => 'Lainnya',
                            ])
                            ->default('merdeka')
                            ->required(),
                        TextInput::make('phase')
                            ->label('Fase (mis. D)')
                            ->maxLength(10),
                        Select::make('semester')
                            ->options([
                                '1' => 'Semester 1',
                                '2' => 'Semester 2',
                                'tahunan' => 'Tahunan',
                            ])
                            ->default('tahunan')
                            ->required(),
                        TextInput::make('academic_year')
                            ->label('Tahun Ajaran (mis. 2025/2026)')
                            ->maxLength(20),
                        TagsInput::make('tags')
                            ->label('Tag')
                            ->columnSpanFull(),
                    ]),

                Section::make('Berkas')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('File Materi')
                            ->disk('public')
                            ->directory('materials')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/zip',
                                'application/x-zip-compressed',
                            ])
                            ->maxSize(20480)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state && method_exists($state, 'getClientOriginalName')) {
                                    $set('file_name', $state->getClientOriginalName());
                                    $set('file_size', $state->getSize());
                                    $set('file_mime', $state->getMimeType());
                                }
                            })
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('file_name')->label('Nama File')->maxLength(255),
                        TextInput::make('file_size')->label('Ukuran (bytes)')->numeric(),
                        TextInput::make('file_mime')->label('MIME Type')->maxLength(100),
                        FileUpload::make('cover_image')
                            ->label('Cover (opsional)')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('materials/covers')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_public')->label('Akses Publik')->default(true),
                        Toggle::make('is_featured')->label('Unggulan')->default(false),
                        Toggle::make('is_active')->label('Aktif')->default(true),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->default(now())
                            ->native(false),
                        TextInput::make('order')->label('Urutan')->numeric()->default(0),
                    ]),
            ]);
    }
}
