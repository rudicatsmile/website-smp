<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans\RelationManagers;

use App\Models\CurriculumPlanTopic;
use App\Models\KkoLevel;
use App\Models\LearningMedia;
use App\Models\LearningMethod;
use App\Models\LearningObjective;
use App\Services\CurriculumPlanService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TopicsRelationManager extends RelationManager
{
    protected static string $relationship = 'topics';

    protected static ?string $title = 'Daftar Topik';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('week_number')->label('Minggu ke-')->numeric()->required()->minValue(1),
            TextInput::make('order')->label('Pertemuan ke-')->numeric()->default(0),
            TextInput::make('theme')->label('Wacana / Tema')->maxLength(150)->placeholder('Opsional (misal: Sistem Respirasi)')->columnSpanFull(),
            TextInput::make('topic')->label('Topik / Bab')->required()->maxLength(255)->columnSpanFull(),
            Select::make('learning_objectives')
                ->label('Tujuan Pembelajaran')
                ->multiple()
                ->options(function (Get $get) {
                    $owner = $this->getOwnerRecord();
                    if (! $owner || ! $owner->learning_objective_ids) {
                        return [];
                    }
                    return LearningObjective::whereIn('id', $owner->learning_objective_ids)
                        ->active()
                        ->ordered()
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->columnSpanFull(),
            Repeater::make('learning_paths')
                ->label('Alur Tujuan Pembelajaran')
                ->columns(17)
                ->schema([
                    TextInput::make('description')
                        ->label('Deskripsi ATP')
                        ->required()
                        ->maxLength(500)
                        ->columnSpan(10),
                    Select::make('kko_level_id')
                        ->label('Level KKO')
                        ->options(fn () => KkoLevel::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpan(7),
                ])
                ->addActionLabel('Tambah ATP')
                ->reorderable(false)
                ->collapsible(false)
                ->itemLabel(fn () => null)
                ->defaultItems(0)
                ->extraAttributes(['class' => 'repeater-inline'])
                ->columnSpanFull(),
            Select::make('methods')
                ->label('Metode')
                ->multiple()
                ->options(function (Get $get) {
                    $owner = $this->getOwnerRecord();
                    if (! $owner || ! $owner->default_methods) {
                        return [];
                    }
                    return LearningMethod::whereIn('id', $owner->default_methods)
                        ->active()
                        ->ordered()
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload(),
            Select::make('media')
                ->label('Media')
                ->multiple()
                ->options(function (Get $get) {
                    $owner = $this->getOwnerRecord();
                    if (! $owner || ! $owner->default_media) {
                        return [];
                    }
                    return LearningMedia::whereIn('id', $owner->default_media)
                        ->active()
                        ->ordered()
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload(),
            Textarea::make('assessment_plan')->label('Rencana Penilaian')->rows(2)->columnSpanFull(),
            TextInput::make('default_duration_minutes')->label('Durasi (menit)')->numeric()->default(90),
            Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('topic')
            ->columns([
                TextColumn::make('index')->rowIndex()->label('No'),
                TextColumn::make('week_number')->label('Minggu Ke-')->sortable()->badge(),
                TextColumn::make('order')->label('Pertemuan Ke-')->sortable(),
                TextColumn::make('topic')->label('Topik')->searchable()->limit(50),
                TextColumn::make('methods_display')
                    ->label('Metode')
                    ->getStateUsing(function ($record) {
                        $ids = $record->methods;
                        if (empty($ids)) {
                            return '-';
                        }
                        if (is_string($ids)) {
                            $ids = json_decode($ids, true);
                        }
                        if (! is_array($ids) || empty($ids)) {
                            return '-';
                        }
                        return LearningMethod::whereIn('id', $ids)->ordered()->pluck('name')->implode(', ');
                    })
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('default_duration_minutes')->label('Durasi')->suffix(' mnt')->toggleable(),
            ])
            ->groups([
                \Filament\Tables\Grouping\Group::make('theme')
                    ->label('Wacana / Tema')
                    ->collapsible(),
            ])
            ->defaultGroup('theme')
            ->defaultSort('week_number')
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\ReplicateAction::make()
                    ->label('Duplikasi')
                    ->color('info')
                    ->beforeReplicaSaved(function (\Illuminate\Database\Eloquent\Model $replica): void {
                        $replica->topic = $replica->topic . ' (Salinan)';
                    })
                    ->successNotificationTitle('Topik berhasil diduplikasi'),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Rencana Pembelajaran Per Pertemuan')
                    ->modalHeading('Rencana Pembelajaran Per Pertemuan'),
                Action::make('applyToDates')
                    ->label('🗓 Apply ke Tanggal')
                    ->icon('heroicon-o-calendar-days')
                    ->color('success')
                    ->schema([
                        DatePicker::make('start_date')->label('Tanggal Mulai')->required()->native(false),
                        DatePicker::make('end_date')->label('Tanggal Selesai')->required()->native(false),
                        \Filament\Forms\Components\Repeater::make('schedules')
                            ->label('Jadwal Mengajar')
                            ->schema([
                                Select::make('weekday')->label('Hari Aktif')->required()
                                    ->options([
                                        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                                        4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
                                    ]),
                                TextInput::make('start_time')->label('Jam Mulai')->required()->type('time')
                                    ->default('07:30'),
                                TextInput::make('end_time')->label('Jam Selesai')->required()->type('time')
                                    ->default('09:00'),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->required(),
                        TextInput::make('period')->label('Periode (opsional)')->maxLength(50)
                            ->placeholder('Jam ke-1'),
                        Toggle::make('skip_holidays')->label('Lewati hari libur')->default(true),
                        Toggle::make('publish_immediately')->label('Langsung publish')->default(false),
                    ])
                    ->action(function (array $data, $livewire) {
                        $plan = $livewire->getOwnerRecord();
                        $result = app(CurriculumPlanService::class)->applyToDateRange(
                            plan: $plan,
                            startDate: Carbon::parse($data['start_date']),
                            endDate: Carbon::parse($data['end_date']),
                            schedules: $data['schedules'],
                            period: $data['period'] ?? null,
                            skipHolidays: (bool) ($data['skip_holidays'] ?? true),
                            publishImmediately: (bool) ($data['publish_immediately'] ?? false),
                        );

                        Notification::make()
                            ->title("{$result['created']} sesi dibuat, {$result['skipped']} dilewati")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
