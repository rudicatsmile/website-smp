<?php

declare(strict_types=1);

namespace App\Filament\Resources\CounselingTickets\RelationManagers;

use App\Models\CounselingMessage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $title = 'Thread Pesan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('body')->label('Pesan')->rows(4)->required()->columnSpanFull(),
            Toggle::make('is_internal')->label('Catatan Internal (tidak terlihat pelapor)'),
            FileUpload::make('attachments')->label('Lampiran')
                ->multiple()->disk('public')->directory('counseling')
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
                        'student' => 'Siswa',
                        'counselor' => 'Guru BK',
                        'anonymous' => 'Pelapor',
                        default => $s,
                    })
                    ->color(fn ($s) => match ($s) {
                        'counselor' => 'success',
                        'student' => 'info',
                        'anonymous' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')->label('Nama')->placeholder('—')->toggleable(),
                TextColumn::make('staffMember.name')->label('BK')->placeholder('—')->toggleable(),
                TextColumn::make('body')->label('Pesan')->limit(120)->wrap(),
                IconColumn::make('is_internal')->label('Internal')->boolean(),
            ])
            ->defaultSort('created_at')
            ->headerActions([
                Action::make('reply')
                    ->label('Balas Pelapor')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->schema([
                        Textarea::make('body')->label('Pesan')->rows(5)->required(),
                        Toggle::make('is_internal')->label('Catatan Internal (tidak terlihat pelapor)')->default(false),
                        FileUpload::make('attachments')->label('Lampiran')
                            ->multiple()->disk('public')->directory('counseling')
                            ->maxFiles(3)->maxSize(5120),
                    ])
                    ->action(function (array $data, $livewire) {
                        $ticket = $livewire->getOwnerRecord();
                        $staffId = auth()->user()?->staffMember?->id;

                        CounselingMessage::create([
                            'counseling_ticket_id' => $ticket->id,
                            'sender_type' => 'counselor',
                            'user_id' => auth()->id(),
                            'staff_member_id' => $staffId,
                            'body' => $data['body'],
                            'attachments' => $data['attachments'] ?? null,
                            'is_internal' => (bool) ($data['is_internal'] ?? false),
                        ]);

                        $updates = ['last_activity_at' => now()];
                        if ($ticket->status === 'new') {
                            $updates['status'] = 'in_progress';
                        }
                        if (! $ticket->assigned_to && $staffId) {
                            $updates['assigned_to'] = $staffId;
                        }
                        $ticket->update($updates);

                        Notification::make()->title('Balasan terkirim')->success()->send();
                    }),
            ]);
    }
}
