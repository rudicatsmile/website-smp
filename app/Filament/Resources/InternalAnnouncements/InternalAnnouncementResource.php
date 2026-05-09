<?php

declare(strict_types=1);

namespace App\Filament\Resources\InternalAnnouncements;

use App\Filament\Resources\InternalAnnouncements\Pages\CreateInternalAnnouncement;
use App\Filament\Resources\InternalAnnouncements\Pages\EditInternalAnnouncement;
use App\Filament\Resources\InternalAnnouncements\Pages\ListInternalAnnouncements;
use App\Filament\Resources\InternalAnnouncements\Pages\ViewInternalAnnouncement;
use App\Filament\Resources\InternalAnnouncements\Schemas\InternalAnnouncementForm;
use App\Filament\Resources\InternalAnnouncements\Tables\InternalAnnouncementsTable;
use App\Models\InternalAnnouncement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InternalAnnouncementResource extends Resource
{
    protected static ?string $model = InternalAnnouncement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Pengumuman Internal';

    protected static ?string $modelLabel = 'Pengumuman Internal';

    protected static ?string $pluralModelLabel = 'Pengumuman Internal';

    public static function form(Schema $schema): Schema
    {
        return InternalAnnouncementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InternalAnnouncementsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin', 'editor'])) {
            $query = $query->active()->published()->forUser($user);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user && $user->hasAnyRole(['super_admin', 'admin', 'editor']);
    }

    public static function canEdit($record): bool
    {
        return self::canCreate();
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();

        return $user && $user->hasAnyRole(['super_admin', 'admin']);
    }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();
        if (! $user) {
            return null;
        }

        if ($user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin', 'editor'])) {
            $unread = InternalAnnouncement::query()
                ->active()
                ->published()
                ->forUser($user)
                ->whereDoesntHave('acknowledgements', fn ($q) => $q->where('user_id', $user->id))
                ->count();

            return $unread > 0 ? (string) $unread : null;
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInternalAnnouncements::route('/'),
            'create' => CreateInternalAnnouncement::route('/create'),
            'view' => ViewInternalAnnouncement::route('/{record}'),
            'edit' => EditInternalAnnouncement::route('/{record}/edit'),
        ];
    }
}
