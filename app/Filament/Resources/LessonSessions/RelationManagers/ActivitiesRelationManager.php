<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Log Aktivitas';

    protected static ?string $modelLabel = 'Log';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        default   => ucfirst($state),
                    }),
                TextColumn::make('causer.name')
                    ->label('Oleh')
                    ->placeholder('Sistem'),
                TextColumn::make('properties')
                    ->label('Perubahan')
                    ->getStateUsing(function ($record) {
                        $props = $record->properties;
                        if ($props->isEmpty()) {
                            return '—';
                        }

                        $old = $props->get('old', []);
                        $new = $props->get('attributes', []);

                        if (empty($old) && empty($new)) {
                            return '—';
                        }

                        $changes = [];
                        foreach ($new as $key => $value) {
                            $label = str_replace('_', ' ', ucfirst($key));
                            $oldVal = $old[$key] ?? '—';
                            $newVal = $value ?? '—';

                            if (is_array($oldVal)) $oldVal = implode(', ', $oldVal);
                            if (is_array($newVal)) $newVal = implode(', ', $newVal);

                            if ($oldVal != $newVal) {
                                $changes[] = "{$label}: {$oldVal} → {$newVal}";
                            }
                        }

                        return implode(' | ', $changes) ?: '—';
                    })
                    ->wrap()
                    ->limit(80),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
