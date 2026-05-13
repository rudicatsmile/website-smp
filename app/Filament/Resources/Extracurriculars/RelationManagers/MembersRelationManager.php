<?php

declare(strict_types=1);

namespace App\Filament\Resources\Extracurriculars\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $title = 'Anggota & Pendaftar';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.schoolClass.name')
                    ->label('Kelas')
                    ->badge(),

                TextColumn::make('note')
                    ->label('Motivasi')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->note),

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

                TextColumn::make('decided_at')
                    ->label('Tgl Keputusan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'approved' => 'Diterima', 'rejected' => 'Ditolak']),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $ekskul = $record->extracurricular;
                        if ($ekskul->quota) {
                            $approved = $ekskul->members()->where('status', 'approved')->count();
                            if ($approved >= $ekskul->quota) {
                                Notification::make()->title('Kuota penuh! Tidak bisa menerima anggota baru.')->danger()->send();
                                return;
                            }
                        }
                        $record->update([
                            'status'      => 'approved',
                            'decided_at'  => now(),
                            'decided_by'  => auth()->id(),
                        ]);
                        Notification::make()->title('Anggota diterima.')->success()->send();
                    })
                    ->requiresConfirmation(),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update([
                            'status'     => 'rejected',
                            'decided_at' => now(),
                            'decided_by' => auth()->id(),
                        ]);
                        Notification::make()->title('Pendaftaran ditolak.')->success()->send();
                    })
                    ->requiresConfirmation(),

                EditAction::make()->visible(false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_approve')
                        ->label('Terima Semua Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $ekskul = $records->first()?->extracurricular;
                            $approved = $ekskul?->members()->where('status', 'approved')->count() ?? 0;
                            $quota = $ekskul?->quota;
                            $count = 0;
                            foreach ($records->where('status', 'pending') as $record) {
                                if ($quota && ($approved + $count) >= $quota) {
                                    break;
                                }
                                $record->update([
                                    'status'     => 'approved',
                                    'decided_at' => now(),
                                    'decided_by' => auth()->id(),
                                ]);
                                $count++;
                            }
                            Notification::make()->title("{$count} anggota diterima.")->success()->send();
                        })
                        ->requiresConfirmation(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
