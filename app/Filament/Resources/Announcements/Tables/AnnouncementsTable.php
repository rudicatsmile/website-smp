<?php

namespace App\Filament\Resources\Announcements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('message')
                    ->label('Pesan')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('color')
                    ->label('Warna')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'emerald' => 'success',
                        'blue' => 'info',
                        'amber' => 'warning',
                        'rose' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'emerald' => 'Hijau',
                        'blue' => 'Biru',
                        'amber' => 'Kuning',
                        'rose' => 'Merah',
                        default => $state,
                    }),
                TextColumn::make('start_at')
                    ->label('Mulai')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('end_at')
                    ->label('Selesai')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                IconColumn::make('is_dismissible')
                    ->label('Bisa Ditutup')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
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
                SelectFilter::make('color')
                    ->label('Warna')
                    ->options([
                        'emerald' => 'Hijau',
                        'blue' => 'Biru',
                        'amber' => 'Kuning',
                        'rose' => 'Merah',
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
            ->defaultSort('order', 'asc');
    }
}
