<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentPayments;

use App\Filament\Resources\StudentPayments\Pages\CreateStudentPayment;
use App\Filament\Resources\StudentPayments\Pages\EditStudentPayment;
use App\Filament\Resources\StudentPayments\Pages\ListStudentPayments;
use App\Models\Student;
use App\Models\StudentPayment;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class StudentPaymentResource extends Resource
{
    protected static ?string $model = StudentPayment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Tagihan';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 40;

    public static function getNavigationBadge(): ?string
    {
        return (string) StudentPayment::whereIn('status', ['unpaid', 'overdue'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Tagihan')->columns(2)->schema([
                Select::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('type')->label('Jenis')
                    ->options(StudentPayment::TYPES)->required()->default('spp'),
                TextInput::make('period')->label('Periode')->required()
                    ->placeholder('Contoh: Januari 2026')->maxLength(48),
                TextInput::make('amount')->label('Jumlah (Rp)')->numeric()->required()->minValue(0),
                DatePicker::make('due_date')->label('Jatuh Tempo'),
                Select::make('status')->label('Status')
                    ->options(StudentPayment::STATUSES)->required()->default('unpaid'),
                DateTimePicker::make('paid_at')->label('Tanggal Bayar'),
                TextInput::make('paid_amount')->label('Dibayar (Rp)')->numeric()->minValue(0),
                Textarea::make('note')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->label('Siswa')->searchable()->sortable(),
                TextColumn::make('type_label')->label('Jenis')->badge(),
                TextColumn::make('period')->label('Periode')->searchable(),
                TextColumn::make('amount_formatted')->label('Jumlah'),
                TextColumn::make('due_date')->label('Jatuh Tempo')->date('d M Y')->sortable()->placeholder('—'),
                TextColumn::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'paid' => 'success',
                        'unpaid' => 'warning',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('paid_at')->label('Dibayar')->dateTime('d M Y H:i')->placeholder('—')->toggleable(),
            ])
            ->defaultSort('due_date', 'desc')
            ->filters([
                SelectFilter::make('status')->options(StudentPayment::STATUSES),
                SelectFilter::make('type')->options(StudentPayment::TYPES),
                SelectFilter::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markPaid')
                        ->label('Tandai Lunas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(fn ($r) => $r->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                                'paid_amount' => $r->paid_amount ?? $r->amount,
                            ]));
                            Notification::make()->title('Ditandai lunas')->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentPayments::route('/'),
            'create' => CreateStudentPayment::route('/create'),
            'edit' => EditStudentPayment::route('/{record}/edit'),
        ];
    }
}
