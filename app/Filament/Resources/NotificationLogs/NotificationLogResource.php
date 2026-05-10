<?php

declare(strict_types=1);

namespace App\Filament\Resources\NotificationLogs;

use App\Filament\Resources\NotificationLogs\Pages\ListNotificationLogs;
use App\Filament\Resources\NotificationLogs\Pages\ViewNotificationLog;
use App\Jobs\SendNotificationJob;
use App\Models\NotificationLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class NotificationLogResource extends Resource
{
    protected static ?string $model = NotificationLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'Log Notifikasi';

    protected static ?string $modelLabel = 'Log Notifikasi';

    protected static ?string $pluralModelLabel = 'Log Notifikasi';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan Umum';

    protected static ?int $navigationSort = 90;

    public static function getNavigationBadge(): ?string
    {
        return (string) NotificationLog::where('status', 'failed')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Notifikasi')->columns(2)->schema([
                \Filament\Infolists\Components\TextEntry::make('event_label')->label('Event'),
                \Filament\Infolists\Components\TextEntry::make('channel_label')->label('Kanal'),
                \Filament\Infolists\Components\TextEntry::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    }),
                \Filament\Infolists\Components\TextEntry::make('sent_at')->label('Terkirim')->dateTime('d M Y H:i')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('recipient_name')->label('Nama Penerima')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('recipient_phone')->label('No. HP')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('recipient_email')->label('Email')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('triggerer.name')->label('Dipicu Oleh')->placeholder('Sistem'),
            ]),
            Section::make('Pesan')->schema([
                \Filament\Infolists\Components\TextEntry::make('subject')->label('Subjek')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('message')->label('Isi')->prose(),
            ]),
            Section::make('Error & Payload')->collapsible()->collapsed()->schema([
                \Filament\Infolists\Components\TextEntry::make('error')->label('Error')->placeholder('—'),
                \Filament\Infolists\Components\TextEntry::make('payload')->label('Payload')
                    ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '—'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M H:i')->sortable(),
                TextColumn::make('event_label')->label('Event')->badge()
                    ->color(fn ($record) => match ($record->event) {
                        'absensi' => 'info',
                        'payment_due' => 'warning',
                        'announcement' => 'primary',
                        'rapor' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('channel_label')->label('Kanal')->badge()
                    ->color(fn ($record) => $record->channel === 'whatsapp' ? 'success' : 'info'),
                TextColumn::make('recipient_name')->label('Penerima')->searchable()->placeholder('—'),
                TextColumn::make('recipient_phone')->label('No HP')->toggleable()->placeholder('—'),
                TextColumn::make('recipient_email')->label('Email')->toggleable(isToggledHiddenByDefault: true)->placeholder('—'),
                TextColumn::make('subject')->label('Subjek')->limit(40)->toggleable(),
                TextColumn::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => match ($record->status) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('sent_at')->label('Terkirim')->dateTime('d M H:i')->placeholder('—')->toggleable(),
                TextColumn::make('error')->label('Error')->limit(40)->toggleable(isToggledHiddenByDefault: true)->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('channel')->options(NotificationLog::CHANNELS),
                SelectFilter::make('event')->options(NotificationLog::EVENTS),
                SelectFilter::make('status')->options(NotificationLog::STATUSES),
            ])
            ->recordActions([
                Action::make('retry')
                    ->label('Kirim Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'failed')
                    ->requiresConfirmation()
                    ->action(function (NotificationLog $record) {
                        $record->update(['status' => 'pending', 'error' => null]);
                        SendNotificationJob::dispatch($record->id)
                            ->onConnection(config('notifications.queue.connection'))
                            ->onQueue(config('notifications.queue.name'));
                        Notification::make()->title('Dikirim ulang')->success()->send();
                    }),
            ])
            ->headerActions([
                Action::make('test_send')
                    ->label('Kirim Tes')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->schema([
                        CheckboxList::make('channels')
                            ->label('Kanal')
                            ->options(['whatsapp' => 'WhatsApp', 'email' => 'Email'])
                            ->required()
                            ->columns(2),
                        TextInput::make('phone')->label('No. HP (WA)')->tel()
                            ->helperText('Format 08xx/628xx, wajib bila memilih WhatsApp'),
                        TextInput::make('email')->label('Email')->email()
                            ->helperText('Wajib bila memilih Email'),
                        TextInput::make('subject')->label('Subjek (email)')
                            ->default('Tes Notifikasi')->maxLength(180),
                        Textarea::make('message')->label('Pesan')->required()->rows(4)
                            ->default('Ini adalah pesan tes notifikasi dari sistem.'),
                    ])
                    ->action(function (array $data) {
                        /** @var \App\Services\Notifications\NotificationService $service */
                        $service = app(\App\Services\Notifications\NotificationService::class);
                        $logs = $service->sendCustom(
                            recipient: [
                                'name'  => 'Tes',
                                'phone' => $data['phone'] ?? null,
                                'email' => $data['email'] ?? null,
                            ],
                            channels: $data['channels'] ?? [],
                            subject: $data['subject'] ?? 'Tes Notifikasi',
                            body: $data['message'],
                            event: 'manual',
                            triggeredBy: auth()->id(),
                        );
                        Notification::make()
                            ->title('Tes diantrekan')
                            ->body(count($logs) . ' pesan masuk antrian.')
                            ->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('retryFailed')
                        ->label('Kirim Ulang (gagal)')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status !== 'failed') {
                                    continue;
                                }
                                $record->update(['status' => 'pending', 'error' => null]);
                                SendNotificationJob::dispatch($record->id)
                                    ->onConnection(config('notifications.queue.connection'))
                                    ->onQueue(config('notifications.queue.name'));
                                $count++;
                            }
                            Notification::make()->title("{$count} notifikasi dikirim ulang")->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotificationLogs::route('/'),
            'view' => ViewNotificationLog::route('/{record}'),
        ];
    }
}
