<?php

namespace App\Filament\Resources\SchoolEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SchoolEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable()
                    ->badge(),
                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'umum' => 'gray',
                        'akademik' => 'info',
                        'ekstrakurikuler' => 'success',
                        'libur' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'umum' => 'Umum',
                        'akademik' => 'Akademik',
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'libur' => 'Libur',
                        default => $state,
                    }),
                IconColumn::make('is_holiday')
                    ->label('Libur')
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'umum' => 'Umum',
                        'akademik' => 'Akademik',
                        'ekstrakurikuler' => 'Ekstrakurikuler',
                        'libur' => 'Libur',
                    ]),
                SelectFilter::make('is_holiday')
                    ->label('Hari Libur')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Non-Aktif',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('event_date', 'asc');
    }
}
