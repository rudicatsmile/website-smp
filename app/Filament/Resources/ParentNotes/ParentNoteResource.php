<?php

declare(strict_types=1);

namespace App\Filament\Resources\ParentNotes;

use App\Filament\Resources\ParentNotes\Pages\CreateParentNote;
use App\Filament\Resources\ParentNotes\Pages\EditParentNote;
use App\Filament\Resources\ParentNotes\Pages\ListParentNotes;
use App\Filament\Resources\ParentNotes\RelationManagers\MessagesRelationManager;
use App\Models\ParentNote;
use App\Models\ParentNoteMessage;
use App\Models\SchoolClass;
use App\Models\Student;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ParentNoteResource extends Resource
{
    protected static ?string $model = ParentNote::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static string|\UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 25;

    protected static ?string $navigationLabel = 'Buku Penghubung';

    protected static ?string $modelLabel = 'Buku Penghubung';

    protected static ?string $pluralModelLabel = 'Buku Penghubung';

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();
        if (! $user) {
            return null;
        }
        $query = ParentNote::query()->whereIn('status', ['open', 'replied']);
        if ($user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            $staffId = $user->staffMember?->id;
            $query->where('homeroom_teacher_id', $staffId);
        }
        return (string) $query->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Info Topik')->columns(3)->schema([
                TextInput::make('code')->label('Kode')->disabled()->dehydrated(false),
                Select::make('status')->label('Status')
                    ->options(ParentNote::STATUSES)->default('open')->required(),
                Select::make('priority')->label('Prioritas')
                    ->options(ParentNote::PRIORITIES)->default('medium')->required(),
                Select::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $student = Student::with('schoolClass')->find($state);
                        if ($student) {
                            $set('school_class_id', $student->school_class_id);
                            $set('homeroom_teacher_id', $student->schoolClass?->homeroom_teacher_id);
                        }
                    })
                    ->live(),
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload(),
                Select::make('category')->label('Kategori')
                    ->options(ParentNote::CATEGORIES)->required()->default('akademik'),
            ]),
            Section::make('Topik')->schema([
                TextInput::make('subject')->label('Subjek')->required()->maxLength(200)->columnSpanFull(),
                Textarea::make('initial_body')->label('Pesan Awal (opsional)')
                    ->helperText('Jika diisi, pesan ini akan tersimpan sebagai pesan pertama.')
                    ->rows(4)->columnSpanFull()
                    ->dehydrated(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Kode')->searchable()->badge()->color('info'),
                TextColumn::make('subject')->label('Subjek')->searchable()->limit(40),
                TextColumn::make('student.name')->label('Siswa')->searchable()->sortable(),
                TextColumn::make('schoolClass.name')->label('Kelas')->toggleable(),
                TextColumn::make('homeroomTeacher.name')->label('Wali Kelas')->placeholder('—')->toggleable(),
                TextColumn::make('category_label')->label('Kategori')->badge()->toggleable(),
                TextColumn::make('priority_label')->label('Prioritas')->badge()
                    ->color(fn ($record) => $record->priority_color),
                TextColumn::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => $record->status_color),
                TextColumn::make('initiator_type')->label('Diinisiasi')->badge()
                    ->formatStateUsing(fn ($s) => $s === 'teacher' ? 'Wali Kelas' : 'Orang Tua')
                    ->color(fn ($s) => $s === 'teacher' ? 'success' : 'info')
                    ->toggleable(),
                TextColumn::make('messages_count')->label('Pesan')->counts('messages')->badge()->toggleable(),
                TextColumn::make('last_activity_at')->label('Aktivitas')->since()->sortable(),
            ])
            ->defaultSort('last_activity_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(ParentNote::STATUSES),
                SelectFilter::make('priority')->options(ParentNote::PRIORITIES),
                SelectFilter::make('category')->options(ParentNote::CATEGORIES),
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id')),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markResolved')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(fn ($r) => $r->update([
                                'status' => 'resolved',
                                'resolved_at' => now(),
                                'last_activity_at' => now(),
                            ]));
                            Notification::make()->title('Topik ditandai selesai')->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [MessagesRelationManager::class];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            $staffId = $user->staffMember?->id;
            $query->where('homeroom_teacher_id', $staffId);
        }
        return $query;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListParentNotes::route('/'),
            'create' => CreateParentNote::route('/create'),
            'edit' => EditParentNote::route('/{record}/edit'),
        ];
    }
}
