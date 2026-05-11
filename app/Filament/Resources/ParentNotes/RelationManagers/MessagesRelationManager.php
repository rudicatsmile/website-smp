<?php

declare(strict_types=1);

namespace App\Filament\Resources\ParentNotes\RelationManagers;

use App\Models\ParentNoteMessage;
use App\Services\Notifications\ParentNoteNotifier;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $title = 'Thread Pesan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('body')->label('Pesan')->rows(4)->required()->columnSpanFull(),
            Toggle::make('is_internal')->label('Catatan Internal (tidak terlihat orang tua)'),
            FileUpload::make('attachments')->label('Lampiran')
                ->multiple()->disk('public')->directory('parent-notes')
                ->maxFiles(3)->maxSize(5120)->openable()->downloadable()
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('sender_type')->label('Pengirim')->badge()
                    ->formatStateUsing(fn ($s) => match ($s) {
                        'parent' => 'Orang Tua',
                        'teacher' => 'Wali Kelas',
                        'system' => 'Sistem',
                        default => $s,
                    })
                    ->color(fn ($s) => match ($s) {
                        'teacher' => 'success',
                        'parent' => 'info',
                        'system' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')->label('Nama')->placeholder('—')->toggleable(),
                TextColumn::make('staffMember.name')->label('Wali Kelas')->placeholder('—')->toggleable(),
                TextColumn::make('body')->label('Pesan')->limit(120)->wrap(),
                IconColumn::make('is_internal')->label('Internal')->boolean(),
            ])
            ->defaultSort('created_at')
            ->headerActions([
                Action::make('reply')
                    ->label('Balas Orang Tua')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->schema([
                        Textarea::make('body')->label('Pesan')->rows(5)->required(),
                        Toggle::make('is_internal')->label('Catatan Internal (tidak terlihat orang tua)')->default(false),
                        FileUpload::make('attachments')->label('Lampiran')
                            ->multiple()->disk('public')->directory('parent-notes')
                            ->maxFiles(3)->maxSize(5120),
                    ])
                    ->action(function (array $data, $livewire) {
                        $note = $livewire->getOwnerRecord();
                        $staffId = auth()->user()?->staffMember?->id;

                        $message = ParentNoteMessage::create([
                            'parent_note_id' => $note->id,
                            'sender_type' => 'teacher',
                            'user_id' => auth()->id(),
                            'staff_member_id' => $staffId,
                            'body' => $data['body'],
                            'attachments' => $data['attachments'] ?? null,
                            'is_internal' => (bool) ($data['is_internal'] ?? false),
                        ]);

                        $updates = ['last_activity_at' => now()];
                        if ($note->status === 'open') {
                            $updates['status'] = 'replied';
                        }
                        if (! $note->homeroom_teacher_id && $staffId) {
                            $updates['homeroom_teacher_id'] = $staffId;
                        }
                        $note->update($updates);

                        app(ParentNoteNotifier::class)->dispatchForMessage($message);

                        Notification::make()->title('Balasan terkirim')->success()->send();
                    }),
            ]);
    }
}
