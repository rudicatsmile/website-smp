<?php

declare(strict_types=1);

namespace App\Filament\Resources\Extracurriculars;

use App\Filament\Resources\Extracurriculars\Pages\ListExtracurricularMembers;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ExtracurricularMemberResource extends Resource
{
    protected static ?string $model = ExtracurricularMember::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Ekstrakurikuler';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'Pendaftaran Ekskul';

    protected static ?string $modelLabel = 'Pendaftaran';

    protected static ?string $pluralModelLabel = 'Pendaftaran Ekskul';

    protected static ?string $slug = 'ekskul-anggota';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('extracurricular.cover')
                    ->label('')
                    ->square()
                    ->size(40)
                    ->defaultImageUrl(fn () => null),

                TextColumn::make('extracurricular.name')
                    ->label('Ekskul')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.schoolClass.name')
                    ->label('Kelas')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('note')
                    ->label('Motivasi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->note)
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'approved' => 'Diterima',
                        'rejected' => 'Ditolak',
                        default    => 'Pending',
                    }),

                TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('decided_at')
                    ->label('Tgl Keputusan')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('decider.name')
                    ->label('Diputuskan Oleh')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('extracurricular_id')
                    ->label('Ekskul')
                    ->options(fn () => Extracurricular::active()->ordered()->pluck('name', 'id'))
                    ->searchable(),

            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Terima Pendaftaran')
                    ->modalDescription(fn ($record) => "Terima {$record->student->name} sebagai anggota {$record->extracurricular->name}?")
                    ->action(function ($record) {
                        $ekskul = $record->extracurricular;
                        if ($ekskul->quota) {
                            $approved = $ekskul->members()->where('status', 'approved')->count();
                            if ($approved >= $ekskul->quota) {
                                Notification::make()
                                    ->title('Kuota penuh!')
                                    ->body('Tidak dapat menerima anggota baru, kuota sudah terpenuhi.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                        }
                        $record->update([
                            'status'     => 'approved',
                            'decided_at' => now(),
                            'decided_by' => auth()->id(),
                        ]);
                        Notification::make()->title('Anggota diterima.')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pendaftaran')
                    ->modalDescription(fn ($record) => "Tolak pendaftaran {$record->student->name} untuk ekskul {$record->extracurricular->name}?")
                    ->action(function ($record) {
                        $record->update([
                            'status'     => 'rejected',
                            'decided_at' => now(),
                            'decided_by' => auth()->id(),
                        ]);
                        Notification::make()->title('Pendaftaran ditolak.')->success()->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_approve')
                        ->label('Terima Semua Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records->where('status', 'pending') as $record) {
                                $ekskul   = $record->extracurricular;
                                $approved = $ekskul->members()->where('status', 'approved')->count();
                                if ($ekskul->quota && $approved >= $ekskul->quota) {
                                    continue;
                                }
                                $record->update([
                                    'status'     => 'approved',
                                    'decided_at' => now(),
                                    'decided_by' => auth()->id(),
                                ]);
                                $count++;
                            }
                            Notification::make()->title("{$count} anggota diterima.")->success()->send();
                        }),

                    BulkAction::make('bulk_reject')
                        ->label('Tolak Semua Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = $records->where('status', 'pending')->count();
                            $records->where('status', 'pending')->each(fn ($r) => $r->update([
                                'status'     => 'rejected',
                                'decided_at' => now(),
                                'decided_by' => auth()->id(),
                            ]));
                            Notification::make()->title("{$count} pendaftaran ditolak.")->success()->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExtracurricularMembers::route('/'),
        ];
    }
}
