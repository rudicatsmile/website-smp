<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentAttendances;

use App\Filament\Resources\StudentAttendances\Pages\CreateStudentAttendance;
use App\Filament\Resources\StudentAttendances\Pages\EditStudentAttendance;
use App\Filament\Resources\StudentAttendances\Pages\ListStudentAttendances;
use App\Models\StaffMember;
use App\Models\Student;
use App\Models\StudentAttendance;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class StudentAttendanceResource extends Resource
{
    protected static ?string $model = StudentAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Absensi Siswa';

    protected static ?string $modelLabel = 'Absensi';

    protected static ?string $pluralModelLabel = 'Absensi Siswa';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Absensi')->columns(2)->schema([
                Select::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                DatePicker::make('date')->label('Tanggal')->required()->default(today()),
                Select::make('status')->label('Status')
                    ->options(StudentAttendance::STATUSES)->required()->default('hadir'),
                Select::make('staff_member_id')->label('Pencatat')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                Textarea::make('note')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('student.name')->label('Siswa')->searchable()->sortable(),
                TextColumn::make('student.schoolClass.name')->label('Kelas')->toggleable(),
                TextColumn::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'hadir' => 'success',
                        'izin' => 'info',
                        'sakit' => 'warning',
                        'alpa' => 'danger',
                        'terlambat' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('note')->label('Catatan')->limit(30)->toggleable(),
                TextColumn::make('recorder.name')->label('Pencatat')->toggleable()->placeholder('—'),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('status')->options(StudentAttendance::STATUSES),
                SelectFilter::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id')),
                Filter::make('month')
                    ->label('Bulan ini')
                    ->query(fn (Builder $q) => $q->whereMonth('date', now()->month)->whereYear('date', now()->year)),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentAttendances::route('/'),
            'create' => CreateStudentAttendance::route('/create'),
            'edit' => EditStudentAttendance::route('/{record}/edit'),
        ];
    }
}
