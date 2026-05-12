<?php

declare(strict_types=1);

namespace App\Filament\Resources\CurriculumPlans\RelationManagers;

use App\Models\CurriculumPlanTopic;
use App\Services\CurriculumPlanService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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
            TextInput::make('week_number')->label('Pekan ke-')->numeric()->required()->minValue(1),
            TextInput::make('order')->label('Urutan')->numeric()->default(0),
            TextInput::make('topic')->label('Topik / Bab')->required()->maxLength(255)->columnSpanFull(),
            Textarea::make('learning_objectives')->label('Tujuan Pembelajaran')->rows(3)->columnSpanFull(),
            TextInput::make('methods')->label('Metode')->maxLength(255),
            TextInput::make('media')->label('Media')->maxLength(255),
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
                TextColumn::make('week_number')->label('Pekan')->sortable()->badge(),
                TextColumn::make('order')->label('#')->sortable(),
                TextColumn::make('topic')->label('Topik')->searchable()->limit(50),
                TextColumn::make('methods')->label('Metode')->limit(30)->toggleable(),
                TextColumn::make('default_duration_minutes')->label('Durasi')->suffix(' mnt')->toggleable(),
            ])
            ->defaultSort('week_number')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                Action::make('applyToDates')
                    ->label('🗓 Apply ke Tanggal')
                    ->icon('heroicon-o-calendar-days')
                    ->color('success')
                    ->schema([
                        DatePicker::make('start_date')->label('Tanggal Mulai')->required()->native(false),
                        DatePicker::make('end_date')->label('Tanggal Selesai')->required()->native(false),
                        Select::make('weekdays')->label('Hari Aktif')->multiple()->required()
                            ->options([
                                1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
                            ])
                            ->default([1, 2, 3, 4, 5]),
                        TextInput::make('start_time')->label('Jam Mulai')->required()->type('time')
                            ->default('07:30'),
                        TextInput::make('end_time')->label('Jam Selesai')->required()->type('time')
                            ->default('09:00'),
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
                            weekdays: array_map('intval', $data['weekdays']),
                            startTime: $data['start_time'],
                            endTime: $data['end_time'],
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
