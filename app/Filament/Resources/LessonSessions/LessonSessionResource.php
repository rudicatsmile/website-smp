<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions;

use App\Filament\Resources\LessonSessions\Pages\CreateLessonSession;
use App\Filament\Resources\LessonSessions\Pages\EditLessonSession;
use App\Filament\Resources\LessonSessions\Pages\ListLessonSessions;
use App\Filament\Resources\LessonSessions\RelationManagers\AssignmentsRelationManager;
use App\Filament\Resources\LessonSessions\RelationManagers\MaterialsRelationManager;
use App\Models\LessonSession;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use App\Services\LessonExecutionService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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

class LessonSessionResource extends Resource
{
    protected static ?string $model = LessonSession::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|\UnitEnum|null $navigationGroup = 'Materi Pelajaran';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Sesi Mengajar';

    protected static ?string $modelLabel = 'Sesi Mengajar';

    protected static ?string $pluralModelLabel = 'Sesi Mengajar';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Jadwal')->columns(3)->schema([
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('material_category_id')->label('Mata Pelajaran')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('staff_member_id')->label('Guru')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                DatePicker::make('session_date')->label('Tanggal')->required()->native(false),
                TextInput::make('start_time')->label('Jam Mulai')->type('time')->required(),
                TextInput::make('end_time')->label('Jam Selesai')->type('time')->required(),
                TextInput::make('period')->label('Periode')->maxLength(50)->placeholder('Jam ke-1'),
                Select::make('status')->label('Status')
                    ->options(LessonSession::STATUSES)->default('draft')->required(),
            ]),
            Section::make('Konten Pembelajaran')->schema([
                TextInput::make('topic')->label('Topik / Bab')->required()->maxLength(255)->columnSpanFull(),
                Textarea::make('learning_objectives')->label('Tujuan Pembelajaran')->rows(3)->columnSpanFull(),
                TextInput::make('methods')->label('Metode')->maxLength(255),
                TextInput::make('media')->label('Media')->maxLength(255),
                Textarea::make('assessment_plan')->label('Rencana Penilaian')->rows(2)->columnSpanFull(),
                Textarea::make('notes')->label('Catatan Rencana')->rows(2)->columnSpanFull(),
            ]),
            Section::make('Realisasi (diisi guru setelah mengajar)')->schema([
                TextInput::make('achievement_percent')->label('Pencapaian (%)')->numeric()->minValue(0)->maxValue(100),
                Textarea::make('execution_notes')->label('Catatan Kegiatan')->rows(3)->columnSpanFull(),
                Textarea::make('homework_notes')->label('Tugas / PR')->rows(2)->columnSpanFull(),
                Textarea::make('issues_notes')->label('Kendala')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('session_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('time_range')->label('Jam'),
                TextColumn::make('schoolClass.name')->label('Kelas')->badge(),
                TextColumn::make('subject.name')->label('Mapel')->badge()->color('info'),
                TextColumn::make('teacher.name')->label('Guru')->toggleable(),
                TextColumn::make('topic')->label('Topik')->searchable()->limit(35),
                TextColumn::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => $record->status_color),
                TextColumn::make('achievement_percent')->label('Pencapaian')
                    ->formatStateUsing(fn ($s) => $s !== null ? "{$s}%" : '—')
                    ->toggleable(),
            ])
            ->defaultSort('session_date', 'desc')
            ->filters([
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::ordered()->pluck('name', 'id')),
                SelectFilter::make('material_category_id')->label('Mapel')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id')),
                SelectFilter::make('staff_member_id')->label('Guru')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id')),
                SelectFilter::make('status')->options(LessonSession::STATUSES),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulkPublish')->label('Publish')->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(fn ($r) => $r->update(['status' => 'published']));
                            Notification::make()->title('Sesi dipublish')->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('bulkCancel')->label('Batalkan')->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->each(fn ($r) => $r->update(['status' => 'cancelled', 'cancelled_reason' => 'Dibatalkan massal']));
                            Notification::make()->title('Sesi dibatalkan')->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [MaterialsRelationManager::class, AssignmentsRelationManager::class];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            $staffId = $user->staffMember?->id;
            $query->where('staff_member_id', $staffId);
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
            'index' => ListLessonSessions::route('/'),
            'create' => CreateLessonSession::route('/create'),
            'edit' => EditLessonSession::route('/{record}/edit'),
        ];
    }
}
