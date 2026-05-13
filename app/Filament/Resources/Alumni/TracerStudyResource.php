<?php

declare(strict_types=1);

namespace App\Filament\Resources\Alumni;

use App\Filament\Resources\Alumni\Pages\ListTracerStudies;
use App\Models\Alumni;
use App\Models\TracerStudy;
use Illuminate\Support\Str;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class TracerStudyResource extends Resource
{
    protected static ?string $model = TracerStudy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Alumni';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Tracer Study';

    protected static ?string $modelLabel = 'Respons Tracer Study';

    protected static ?string $pluralModelLabel = 'Tracer Study';

    protected static ?string $slug = 'alumni/tracer-study';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('graduation_year')
                    ->label('Thn Lulus')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('current_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'working'      => 'success',
                        'studying'     => 'info',
                        'entrepreneur' => 'warning',
                        'both'         => 'primary',
                        'unemployed'   => 'danger',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'working'      => 'Bekerja',
                        'studying'     => 'Kuliah',
                        'entrepreneur' => 'Wirausaha',
                        'both'         => 'Kuliah & Kerja',
                        'unemployed'   => 'Belum Bekerja',
                        default        => 'Lainnya',
                    }),

                TextColumn::make('company_or_institution')
                    ->label('Perusahaan/Inst.')
                    ->limit(28)
                    ->placeholder('—'),

                TextColumn::make('school_relevance')
                    ->label('Relevansi Sekolah')
                    ->formatStateUsing(fn ($state) => $state ? str_repeat('★', $state) . str_repeat('☆', 5 - $state) : '—')
                    ->alignCenter(),

                TextColumn::make('school_quality')
                    ->label('Kualitas Sekolah')
                    ->formatStateUsing(fn ($state) => $state ? str_repeat('★', $state) . str_repeat('☆', 5 - $state) : '—')
                    ->alignCenter(),

                IconColumn::make('allow_publish')
                    ->label('Izin Tampil')
                    ->boolean(),

                TextColumn::make('is_processed')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? 'Diproses' : 'Pending'),

                TextColumn::make('created_at')
                    ->label('Tgl Submit')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_processed')->label('Sudah Diproses'),

                SelectFilter::make('current_status')
                    ->label('Status')
                    ->options([
                        'working'      => 'Bekerja',
                        'studying'     => 'Kuliah',
                        'entrepreneur' => 'Wirausaha',
                        'both'         => 'Kuliah & Bekerja',
                        'unemployed'   => 'Belum Bekerja',
                        'other'        => 'Lainnya',
                    ]),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Detail — ' . $record->name)
                    ->modalContent(fn ($record) => view('filament.modals.tracer-study-detail', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Action::make('mark_processed')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->is_processed)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'is_processed' => true,
                            'processed_at' => now(),
                            'processed_by' => auth()->id(),
                        ]);

                        $created = false;
                        if ($record->allow_publish) {
                            $slug = Str::slug($record->name);
                            $base = $slug;
                            $i = 1;
                            while (Alumni::where('slug', $slug)->exists()) {
                                $slug = $base . '-' . $i++;
                            }
                            Alumni::firstOrCreate(
                                ['slug' => $slug],
                                [
                                    'name'                   => $record->name,
                                    'graduation_year'        => $record->graduation_year,
                                    'current_status'         => $record->current_status,
                                    'company_or_institution' => $record->company_or_institution,
                                    'position'               => $record->position,
                                    'city'                   => $record->city,
                                    'is_published'           => true,
                                    'is_featured'            => false,
                                    'order'                  => 0,
                                ]
                            );
                            $created = true;
                        }

                        Notification::make()
                            ->title('Ditandai selesai' . ($created ? ' & profil alumni dibuat.' : '.'))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_process')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $pending = $records->where('is_processed', false);
                            $count = $pending->count();
                            $created = 0;

                            $pending->each(function ($r) use (&$created) {
                                $r->update([
                                    'is_processed' => true,
                                    'processed_at' => now(),
                                    'processed_by' => auth()->id(),
                                ]);

                                if ($r->allow_publish) {
                                    $slug = Str::slug($r->name);
                                    $base = $slug;
                                    $i = 1;
                                    while (Alumni::where('slug', $slug)->exists()) {
                                        $slug = $base . '-' . $i++;
                                    }
                                    Alumni::firstOrCreate(
                                        ['slug' => $slug],
                                        [
                                            'name'                   => $r->name,
                                            'graduation_year'        => $r->graduation_year,
                                            'current_status'         => $r->current_status,
                                            'company_or_institution' => $r->company_or_institution,
                                            'position'               => $r->position,
                                            'city'                   => $r->city,
                                            'is_published'           => true,
                                            'is_featured'            => false,
                                            'order'                  => 0,
                                        ]
                                    );
                                    $created++;
                                }
                            });

                            Notification::make()
                                ->title("{$count} respons diproses" . ($created ? ", {$created} profil alumni dibuat." : '.'))
                                ->success()
                                ->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTracerStudies::route('/'),
        ];
    }
}
