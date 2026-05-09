<?php

declare(strict_types=1);

namespace App\Filament\Resources\CounselingTickets;

use App\Filament\Resources\CounselingTickets\Pages\CreateCounselingTicket;
use App\Filament\Resources\CounselingTickets\Pages\EditCounselingTicket;
use App\Filament\Resources\CounselingTickets\Pages\ListCounselingTickets;
use App\Filament\Resources\CounselingTickets\RelationManagers\MessagesRelationManager;
use App\Models\CounselingTicket;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Forms\Components\Placeholder;
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
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CounselingTicketResource extends Resource
{
    protected static ?string $model = CounselingTicket::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Konseling BK';

    protected static ?string $modelLabel = 'Tiket Konseling';

    protected static ?string $pluralModelLabel = 'Tiket Konseling';

    protected static string|\UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 30;

    public static function getNavigationBadge(): ?string
    {
        return (string) CounselingTicket::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Triage')->columns(3)->schema([
                TextInput::make('code')->label('Kode Tiket')->disabled()->dehydrated(false),
                Select::make('status')->label('Status')
                    ->options(CounselingTicket::STATUSES)
                    ->default('new')->required(),
                Select::make('priority')->label('Prioritas')
                    ->options(CounselingTicket::PRIORITIES)
                    ->default('medium')->required(),
                Select::make('category')->label('Kategori')
                    ->options(CounselingTicket::CATEGORIES)
                    ->required(),
                Select::make('assigned_to')->label('Ditugaskan ke')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                Select::make('channel')->label('Kanal')
                    ->options(['public' => 'Publik', 'portal' => 'Portal'])
                    ->default('portal')->disabled()->dehydrated(false),
            ]),
            Section::make('Isi Pengaduan')->schema([
                TextInput::make('subject')->label('Subjek')->disabled()->dehydrated(false)->columnSpanFull(),
                Textarea::make('body')->label('Uraian Pelapor')->rows(6)->disabled()->dehydrated(false)->columnSpanFull(),
                Placeholder::make('attachments_display')
                    ->label('Lampiran')
                    ->content(function ($record) {
                        if (! $record || empty($record->attachments)) return '— tidak ada —';
                        $links = [];
                        foreach ($record->attachments as $path) {
                            $links[] = '<a href="'.asset('storage/'.$path).'" target="_blank" class="text-emerald-700 underline">'.basename($path).'</a>';
                        }
                        return new \Illuminate\Support\HtmlString(implode('<br>', $links));
                    })
                    ->columnSpanFull(),
            ]),
            Section::make('Info Pelapor')->columns(3)->schema([
                Placeholder::make('reporter')->label('Pelapor')
                    ->content(fn ($record) => $record?->user?->name ?? $record?->reporter_name ?? 'Anonim'),
                Placeholder::make('contact')->label('Kontak')
                    ->content(fn ($record) => $record?->reporter_contact ?? ($record?->user?->email ?? '—')),
                Placeholder::make('created')->label('Dibuat')
                    ->content(fn ($record) => $record?->created_at?->translatedFormat('d M Y H:i') ?? '—'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Kode')->searchable()->badge()->color('info'),
                TextColumn::make('subject')->label('Subjek')->searchable()->limit(40),
                TextColumn::make('category_label')->label('Kategori')->badge()->toggleable(),
                TextColumn::make('priority_label')->label('Prioritas')->badge()
                    ->color(fn ($record) => $record->priority_color),
                TextColumn::make('status_label')->label('Status')->badge()
                    ->color(fn ($record) => $record->status_color),
                TextColumn::make('channel')->label('Kanal')->badge()
                    ->formatStateUsing(fn ($s) => $s === 'public' ? 'Publik' : 'Portal')
                    ->color(fn ($s) => $s === 'public' ? 'warning' : 'info')
                    ->toggleable(),
                TextColumn::make('reporter_display')->label('Pelapor')
                    ->state(fn ($record) => $record->user?->name ?? $record->reporter_name ?? 'Anonim'),
                TextColumn::make('assignee.name')->label('Ditugaskan')->placeholder('—')->toggleable(),
                TextColumn::make('messages_count')->label('Pesan')->counts('messages')->badge()->toggleable(),
                TextColumn::make('last_activity_at')->label('Aktivitas')->since()->sortable(),
            ])
            ->defaultSort('last_activity_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(CounselingTicket::STATUSES),
                SelectFilter::make('priority')->options(CounselingTicket::PRIORITIES),
                SelectFilter::make('category')->options(CounselingTicket::CATEGORIES),
                SelectFilter::make('channel')->options(['public' => 'Publik', 'portal' => 'Portal']),
                SelectFilter::make('assigned_to')->label('Ditugaskan')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markResolved')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(fn ($r) => $r->update([
                                'status' => 'resolved',
                                'resolved_at' => now(),
                                'last_activity_at' => now(),
                            ]));
                            Notification::make()->title('Tiket ditandai selesai')->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [MessagesRelationManager::class];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user && $user->hasRole('counselor') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            $staffId = $user->staffMember?->id;
            $query->where(function ($q) use ($staffId) {
                $q->whereNull('assigned_to');
                if ($staffId) $q->orWhere('assigned_to', $staffId);
            });
        }
        return $query;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'counselor']) ?? false;
    }

    public static function canCreate(): bool
    {
        return false; // tiket dibuat hanya dari publik/portal
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCounselingTickets::route('/'),
            'create' => CreateCounselingTicket::route('/create'),
            'edit' => EditCounselingTicket::route('/{record}/edit'),
        ];
    }
}
