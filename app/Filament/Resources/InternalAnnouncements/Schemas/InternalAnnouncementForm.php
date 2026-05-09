<?php

declare(strict_types=1);

namespace App\Filament\Resources\InternalAnnouncements\Schemas;

use App\Models\InternalAnnouncement;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class InternalAnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten')
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
                        Select::make('category')
                            ->label('Kategori')
                            ->options(InternalAnnouncement::CATEGORIES)
                            ->default('umum')
                            ->required(),
                        Select::make('priority')
                            ->label('Prioritas')
                            ->options(InternalAnnouncement::PRIORITIES)
                            ->default('normal')
                            ->required(),
                        RichEditor::make('body')
                            ->label('Isi')
                            ->columnSpanFull(),
                    ]),

                Section::make('Target Penerima')
                    ->description('Kosongkan keduanya untuk pengumuman umum (semua role internal).')
                    ->columns(2)
                    ->schema([
                        CheckboxList::make('target_roles')
                            ->label('Target Role')
                            ->options(InternalAnnouncement::TARGET_ROLES)
                            ->columns(3)
                            ->columnSpanFull(),
                        Select::make('target_staff_ids')
                            ->label('Target Guru Spesifik (opsional)')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => \App\Models\StaffMember::active()->orderBy('name')->pluck('name', 'id')->toArray())
                            ->columnSpanFull(),
                    ]),

                Section::make('Lampiran')
                    ->schema([
                        FileUpload::make('attachments')
                            ->label('Lampiran')
                            ->multiple()
                            ->disk('public')
                            ->directory('announcements')
                            ->visibility('public')
                            ->reorderable()
                            ->openable()
                            ->downloadable()
                            ->maxSize(10240)
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'image/jpeg', 'image/png', 'image/webp',
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan')
                    ->columns(4)
                    ->schema([
                        Toggle::make('is_pinned')->label('Sematkan')->default(false),
                        Toggle::make('is_active')->label('Aktif')->default(true),
                        DateTimePicker::make('published_at')
                            ->label('Dipublikasikan')
                            ->default(now())
                            ->native(false),
                        DateTimePicker::make('expires_at')
                            ->label('Kedaluwarsa')
                            ->native(false),
                    ]),
            ]);
    }
}
