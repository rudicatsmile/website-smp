<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassAnnouncements;

use App\Filament\Resources\ClassAnnouncements\Pages\CreateClassAnnouncement;
use App\Filament\Resources\ClassAnnouncements\Pages\EditClassAnnouncement;
use App\Filament\Resources\ClassAnnouncements\Pages\ListClassAnnouncements;
use App\Models\ClassAnnouncement;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Support\Str;

class ClassAnnouncementResource extends Resource
{
    protected static ?string $model = ClassAnnouncement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Pengumuman Kelas';

    protected static ?string $modelLabel = 'Pengumuman Kelas';

    protected static ?string $pluralModelLabel = 'Pengumuman Kelas';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 13;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten')->columns(2)->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state . '-' . now()->format('YmdHis'))))
                    ->columnSpanFull(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->columnSpanFull(),
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->placeholder('— Semua kelas (global) —'),
                Select::make('staff_member_id')->label('Penulis (Guru)')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->default(fn () => auth()->user()?->staffMember?->id),
                RichEditor::make('body')->label('Isi Pengumuman')->columnSpanFull(),
            ]),
            Section::make('Lampiran')->schema([
                FileUpload::make('attachments')->label('Lampiran')->multiple()
                    ->disk('public')->directory('class-announcements')
                    ->openable()->downloadable()->reorderable()->maxSize(10240)
                    ->columnSpanFull(),
            ]),
            Section::make('Publikasi')->columns(4)->schema([
                Toggle::make('pinned')->label('Sematkan')->default(false),
                Toggle::make('is_published')->label('Terbit')->default(true),
                DateTimePicker::make('published_at')->label('Tanggal Terbit')->default(now())->native(false),
                DateTimePicker::make('expires_at')->label('Kedaluwarsa')->native(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('pinned')->label('')->icon(fn ($state) => $state ? 'heroicon-s-bookmark' : '')->color('warning'),
                TextColumn::make('title')->label('Judul')->searchable()->limit(50),
                TextColumn::make('schoolClass.name')->label('Kelas')->badge()->placeholder('Global'),
                TextColumn::make('teacher.name')->label('Penulis')->toggleable(),
                TextColumn::make('published_at')->label('Terbit')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('expires_at')->label('Kedaluwarsa')->dateTime('d M Y')->placeholder('—')->toggleable(),
                IconColumn::make('is_published')->label('Aktif')->boolean(),
            ])
            ->defaultSort('pinned', 'desc')
            ->filters([
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::ordered()->pluck('name', 'id')),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'editor', 'teacher']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassAnnouncements::route('/'),
            'create' => CreateClassAnnouncement::route('/create'),
            'edit' => EditClassAnnouncement::route('/{record}/edit'),
        ];
    }
}
