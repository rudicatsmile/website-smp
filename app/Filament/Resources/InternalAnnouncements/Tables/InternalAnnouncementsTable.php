<?php

declare(strict_types=1);

namespace App\Filament\Resources\InternalAnnouncements\Tables;

use App\Models\InternalAnnouncement;
use App\Models\InternalAnnouncementAcknowledgement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InternalAnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_pinned')
                    ->label('')
                    ->icon(fn ($state) => $state ? 'heroicon-s-bookmark' : '')
                    ->color('warning'),
                TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->formatStateUsing(fn ($state) => InternalAnnouncement::PRIORITIES[$state] ?? ucfirst((string) $state))
                    ->color(fn ($state) => InternalAnnouncement::PRIORITY_COLORS[$state] ?? 'gray'),
                TextColumn::make('title')->label('Judul')->searchable()->limit(50),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn ($state) => InternalAnnouncement::CATEGORIES[$state] ?? ucfirst((string) $state)),
                TextColumn::make('author.name')->label('Penulis')->toggleable()->searchable(),
                TextColumn::make('published_at')->label('Terbit')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('expires_at')->label('Kedaluwarsa')->dateTime('d M Y')->toggleable()->placeholder('—'),
                TextColumn::make('acknowledgements_count')
                    ->label('Dibaca')
                    ->counts('acknowledgements')
                    ->badge()
                    ->color('success'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('is_pinned', 'desc')
            ->filters([
                SelectFilter::make('category')->options(InternalAnnouncement::CATEGORIES),
                SelectFilter::make('priority')->options(InternalAnnouncement::PRIORITIES),
                TernaryFilter::make('is_pinned')->label('Disematkan'),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('acknowledge')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if (! $user) {
                            return false;
                        }

                        return ! $record->isAcknowledgedBy($user);
                    })
                    ->action(function ($record) {
                        $user = auth()->user();
                        InternalAnnouncementAcknowledgement::firstOrCreate(
                            ['internal_announcement_id' => $record->id, 'user_id' => $user->id],
                            ['acknowledged_at' => now()],
                        );
                        Notification::make()
                            ->title('Pengumuman ditandai sudah dibaca')
                            ->success()
                            ->send();
                    }),
                EditAction::make()->visible(fn () => auth()->user()?->hasAnyRole(['super_admin', 'admin', 'editor']) ?? false),
                DeleteAction::make()->visible(fn () => auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn () => auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false),
                ]),
            ]);
    }
}
