<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeaveRequests;

use App\Filament\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Resources\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Resources\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Resources\LeaveRequests\Pages\ViewLeaveRequest;
use App\Models\LeaveRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\Attendance\LeaveRequestService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Surat Izin';

    protected static ?string $modelLabel = 'Surat Izin';

    protected static ?string $pluralModelLabel = 'Surat Izin';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 25;

    public static function getNavigationBadge(): ?string
    {
        return (string) LeaveRequest::pending()->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Pengajuan')->columns(2)->schema([
                Select::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('type')->label('Jenis Izin')
                    ->options(LeaveRequest::TYPES)->required()->default('izin'),
                DatePicker::make('date_from')->label('Dari Tanggal')->required()->default(today()),
                DatePicker::make('date_to')->label('Sampai Tanggal')->required()->default(today())
                    ->afterOrEqual('date_from'),
                Textarea::make('reason')->label('Alasan')->rows(3)->required()->columnSpanFull(),
                FileUpload::make('attachment')->label('Lampiran (Surat Dokter, dll)')
                    ->disk('public')->directory('leave-requests')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                    ->maxSize(2048)->columnSpanFull(),
            ]),
            Section::make('Status & Review')->columns(2)->schema([
                Select::make('status')->label('Status')
                    ->options(LeaveRequest::STATUSES)->required()->default('pending'),
                Textarea::make('review_note')->label('Catatan Review')->rows(2)->columnSpanFull(),
            ])->collapsed(fn ($record) => $record === null),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pengajuan')->columns(2)->schema([
                \Filament\Infolists\Components\TextEntry::make('student.name')->label('Siswa'),
                \Filament\Infolists\Components\TextEntry::make('student.schoolClass.name')->label('Kelas')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('type_label')->label('Jenis')->badge(),
                \Filament\Infolists\Components\TextEntry::make('date_range_label')->label('Tanggal Izin'),
                \Filament\Infolists\Components\TextEntry::make('day_count')->label('Jumlah Hari')->suffix(' hari'),
                \Filament\Infolists\Components\TextEntry::make('channel_label')->label('Channel')->badge(),
                \Filament\Infolists\Components\TextEntry::make('submitter.name')->label('Pengaju (login)')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('submitter_name')->label('Pengaju (publik)')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('submitter_phone')->label('No. HP Pengaju')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('created_at')->label('Diajukan')->dateTime('d M Y H:i'),
            ]),
            Section::make('Alasan & Lampiran')->schema([
                \Filament\Infolists\Components\TextEntry::make('reason')->label('Alasan')->prose(),
                \Filament\Infolists\Components\TextEntry::make('attachment_url')->label('Lampiran')
                    ->placeholder('Tidak ada')
                    ->url(fn ($record) => $record->attachment_url, true)
                    ->formatStateUsing(fn ($state) => $state ? '📎 Lihat Lampiran' : '—'),
            ]),
            Section::make('Status Review')->columns(2)->schema([
                \Filament\Infolists\Components\TextEntry::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                \Filament\Infolists\Components\TextEntry::make('reviewer.name')->label('Direview Oleh')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('reviewed_at')->label('Tanggal Review')->dateTime('d M Y H:i')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('notification_sent_at')->label('Notifikasi Terkirim')->dateTime('d M Y H:i')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('review_note')->label('Catatan Review')->placeholder('—')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Diajukan')->dateTime('d M H:i')->sortable(),
                TextColumn::make('student.name')->label('Siswa')->searchable()->sortable(),
                TextColumn::make('student.schoolClass.name')->label('Kelas')->toggleable(),
                TextColumn::make('type_label')->label('Jenis')->badge()
                    ->color(fn ($record) => match ($record->type) {
                        'sakit' => 'warning',
                        'izin'  => 'info',
                        'dinas' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('date_range_label')->label('Tanggal Izin'),
                TextColumn::make('day_count')->label('Hari')->suffix(' hr')->alignCenter()->toggleable(),
                TextColumn::make('channel_label')->label('Channel')->badge()->toggleable(),
                TextColumn::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('attachment')->label('Lampiran')
                    ->formatStateUsing(fn ($state) => $state ? '📎' : '—')
                    ->alignCenter()->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(LeaveRequest::STATUSES),
                SelectFilter::make('type')->options(LeaveRequest::TYPES),
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::orderBy('name')->pluck('name', 'id'))
                    ->query(fn (Builder $q, array $data) => $data['value']
                        ? $q->whereHas('student', fn ($qq) => $qq->where('school_class_id', $data['value']))
                        : $q),
                Filter::make('pending_only')->label('Hanya pending')
                    ->query(fn (Builder $q) => $q->where('status', 'pending')),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (LeaveRequest $record) => $record->status !== 'approved')
                    ->schema([
                        Textarea::make('review_note')->label('Catatan (opsional)')->rows(2),
                    ])
                    ->action(function (LeaveRequest $record, array $data) {
                        app(LeaveRequestService::class)->approve(
                            $record,
                            auth()->user(),
                            $data['review_note'] ?? null,
                        );
                        Notification::make()
                            ->title('Izin disetujui')
                            ->body('Absensi siswa otomatis dicatat & notifikasi dikirim ke orang tua.')
                            ->success()->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (LeaveRequest $record) => $record->status !== 'rejected')
                    ->schema([
                        Textarea::make('review_note')->label('Alasan Penolakan')->rows(2)->required(),
                    ])
                    ->action(function (LeaveRequest $record, array $data) {
                        app(LeaveRequestService::class)->reject(
                            $record,
                            auth()->user(),
                            $data['review_note'] ?? null,
                        );
                        Notification::make()->title('Izin ditolak')->success()->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approveAll')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $service = app(LeaveRequestService::class);
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status !== 'approved') {
                                    $service->approve($record, auth()->user());
                                    $count++;
                                }
                            }
                            Notification::make()->title("{$count} pengajuan disetujui")->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'view'   => ViewLeaveRequest::route('/{record}'),
            'edit'   => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
