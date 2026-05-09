<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassAssignments;

use App\Filament\Resources\ClassAssignments\Pages\CreateClassAssignment;
use App\Filament\Resources\ClassAssignments\Pages\EditClassAssignment;
use App\Filament\Resources\ClassAssignments\Pages\ListClassAssignments;
use App\Filament\Resources\ClassAssignments\RelationManagers\SubmissionsRelationManager;
use App\Models\ClassAssignment;
use App\Models\MaterialCategory;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ClassAssignmentResource extends Resource
{
    protected static ?string $model = ClassAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Tugas Kelas';

    protected static ?string $modelLabel = 'Tugas Kelas';

    protected static ?string $pluralModelLabel = 'Tugas Kelas';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 12;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten Tugas')->columns(2)->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state . '-' . now()->format('YmdHis'))))
                    ->columnSpanFull(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->columnSpanFull(),
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('material_category_id')->label('Mata Pelajaran')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                Select::make('staff_member_id')->label('Guru Pengampu')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->default(fn () => auth()->user()?->staffMember?->id),
                DateTimePicker::make('due_at')->label('Deadline')->native(false)->required(),
                TextInput::make('max_score')->label('Skor Maksimum')->numeric()->default(100),
                RichEditor::make('description')->label('Deskripsi Tugas')->columnSpanFull(),
            ]),
            Section::make('Lampiran Materi')->schema([
                FileUpload::make('attachments')->label('Lampiran')->multiple()
                    ->disk('public')->directory('class-assignments')
                    ->openable()->downloadable()->reorderable()->maxSize(10240)
                    ->acceptedFileTypes([
                        'application/pdf', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'image/jpeg', 'image/png', 'image/webp', 'application/zip',
                    ])
                    ->columnSpanFull(),
            ]),
            Section::make('Publikasi')->columns(2)->schema([
                Toggle::make('is_published')->label('Terbitkan')->default(true),
                DateTimePicker::make('published_at')->label('Tanggal Terbit')->default(now())->native(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->limit(40),
                TextColumn::make('schoolClass.name')->label('Kelas')->badge(),
                TextColumn::make('subject.name')->label('Mapel')->toggleable(),
                TextColumn::make('teacher.name')->label('Guru')->toggleable(),
                TextColumn::make('due_at')->label('Deadline')->dateTime('d M Y H:i')->sortable()
                    ->color(fn ($record) => $record->is_overdue ? 'danger' : 'success'),
                TextColumn::make('submissions_count')->label('Submit')->counts('submissions')->badge(),
                IconColumn::make('is_published')->label('Terbit')->boolean(),
            ])
            ->defaultSort('due_at', 'desc')
            ->filters([
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::ordered()->pluck('name', 'id')),
                SelectFilter::make('material_category_id')->label('Mapel')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [SubmissionsRelationManager::class];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin', 'editor'])) {
            $staffId = $user->staffMember?->id;
            $query->where('staff_member_id', $staffId);
        }
        return $query;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher', 'editor']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassAssignments::route('/'),
            'create' => CreateClassAssignment::route('/create'),
            'edit' => EditClassAssignment::route('/{record}/edit'),
        ];
    }
}
