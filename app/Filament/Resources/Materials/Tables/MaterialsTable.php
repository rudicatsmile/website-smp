<?php

declare(strict_types=1);

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->label('Cover')->square(),
                TextColumn::make('title')->label('Judul')->searchable()->sortable()->limit(40),
                TextColumn::make('category.name')->label('Mapel')->searchable()->sortable()->badge(),
                TextColumn::make('author.name')->label('Penulis')->toggleable()->searchable(),
                TextColumn::make('type')->label('Tipe')->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'modul_ajar' => 'Modul Ajar',
                        'rpp' => 'RPP',
                        'lkpd' => 'LKPD',
                        'bahan_ajar' => 'Bahan Ajar',
                        'atp' => 'ATP',
                        'cp' => 'CP',
                        'silabus' => 'Silabus',
                        default => 'Lainnya',
                    }),
                TextColumn::make('grade')->label('Kelas')->badge()
                    ->formatStateUsing(fn ($state) => $state === 'umum' ? 'Umum' : 'Kelas ' . $state),
                TextColumn::make('curriculum')->label('Kurikulum')->badge()->toggleable(),
                IconColumn::make('is_public')->label('Publik')->boolean(),
                IconColumn::make('is_featured')->label('Unggulan')->boolean()->toggleable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('download_count')->label('Unduh')->sortable()->toggleable(),
                TextColumn::make('view_count')->label('Lihat')->sortable()->toggleable(),
                TextColumn::make('published_at')->label('Terbit')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('material_category_id')
                    ->label('Mata Pelajaran')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'modul_ajar' => 'Modul Ajar',
                        'rpp' => 'RPP',
                        'lkpd' => 'LKPD',
                        'bahan_ajar' => 'Bahan Ajar',
                        'atp' => 'ATP',
                        'cp' => 'CP',
                        'silabus' => 'Silabus',
                        'lainnya' => 'Lainnya',
                    ]),
                SelectFilter::make('grade')
                    ->label('Kelas')
                    ->options([
                        '7' => 'Kelas 7',
                        '8' => 'Kelas 8',
                        '9' => 'Kelas 9',
                        'umum' => 'Umum',
                    ]),
                SelectFilter::make('curriculum')
                    ->options([
                        'merdeka' => 'Kurikulum Merdeka',
                        'k13' => 'Kurikulum 2013',
                        'lainnya' => 'Lainnya',
                    ]),
                TernaryFilter::make('is_public')->label('Publik'),
                TernaryFilter::make('is_featured')->label('Unggulan'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
