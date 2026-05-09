<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quizzes\RelationManagers;

use App\Models\BankQuestion;
use App\Models\QuestionBank;
use App\Models\QuizQuestion;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Str as IlluminateStr;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Soal';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')->label('Tipe Soal')->required()
                ->options([
                    'mcq' => 'Pilihan Ganda (1 jawaban)',
                    'multi' => 'Pilihan Ganda (banyak jawaban)',
                    'essay' => 'Essay',
                ])
                ->default('mcq')->live(),
            Textarea::make('body')->label('Pertanyaan')->rows(4)->required()->columnSpanFull(),
            TextInput::make('score')->label('Skor')->numeric()->default(1)->minValue(1)->required(),
            TextInput::make('order')->label('Urutan')->numeric()->default(0),
            Textarea::make('explanation')->label('Pembahasan / Kunci')->rows(3)->columnSpanFull(),
            Repeater::make('options')->label('Opsi Jawaban')
                ->relationship('options')
                ->visible(fn (callable $get) => in_array($get('type'), ['mcq', 'multi']))
                ->schema([
                    Textarea::make('label')->label('Teks Opsi')->rows(2)->required(),
                    Toggle::make('is_correct')->label('Jawaban benar')->inline(false),
                    TextInput::make('order')->label('Urutan')->numeric()->default(0),
                ])
                ->columns(3)
                ->defaultItems(4)
                ->minItems(2)
                ->reorderable()
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                TextColumn::make('order')->label('No')->sortable(),
                TextColumn::make('type')->label('Tipe')->badge()
                    ->formatStateUsing(fn ($s) => match ($s) {
                        'mcq' => 'PG', 'multi' => 'Multi', 'essay' => 'Essay', default => $s,
                    })
                    ->color(fn ($s) => match ($s) {
                        'mcq' => 'info', 'multi' => 'warning', 'essay' => 'gray', default => 'gray',
                    }),
                TextColumn::make('body')->label('Pertanyaan')->html()->limit(80)->wrap(),
                TextColumn::make('score')->label('Skor')->badge(),
                TextColumn::make('options_count')->label('Opsi')->counts('options'),
            ])
            ->defaultSort('order')
            ->headerActions([
                CreateAction::make()->label('Tulis Soal'),
                Action::make('importFromBank')
                    ->label('Import dari Bank Soal')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->schema([
                        Select::make('question_bank_id')
                            ->label('Pilih Bank Soal')
                            ->options(fn () => QuestionBank::active()->orderBy('title')->pluck('title', 'id'))
                            ->searchable()->preload()->required()->live(),
                        Select::make('question_ids')
                            ->label('Pilih Soal')
                            ->multiple()
                            ->options(fn (callable $get) => $get('question_bank_id')
                                ? BankQuestion::where('question_bank_id', $get('question_bank_id'))
                                    ->orderBy('order')
                                    ->get()
                                    ->mapWithKeys(fn ($q) => [$q->id => IlluminateStr::limit(strip_tags($q->body), 80)])
                                    ->toArray()
                                : [])
                            ->visible(fn (callable $get) => $get('question_bank_id') !== null)
                            ->helperText('Kosongkan untuk mengambil sejumlah soal acak.'),
                        TextInput::make('random_count')
                            ->label('Atau ambil acak (jumlah)')
                            ->numeric()->minValue(0),
                    ])
                    ->action(function (array $data, $livewire) {
                        $quiz = $livewire->getOwnerRecord();
                        $bankId = $data['question_bank_id'];
                        $ids = $data['question_ids'] ?? [];
                        $random = (int) ($data['random_count'] ?? 0);

                        $query = BankQuestion::with('options')->where('question_bank_id', $bankId);
                        if (! empty($ids)) {
                            $bankQuestions = $query->whereIn('id', $ids)->orderBy('order')->get();
                        } elseif ($random > 0) {
                            $bankQuestions = $query->inRandomOrder()->limit($random)->get();
                        } else {
                            $bankQuestions = $query->orderBy('order')->get();
                        }

                        $startOrder = (int) ($quiz->questions()->max('order') ?? 0);
                        foreach ($bankQuestions as $bq) {
                            $startOrder++;
                            $qq = QuizQuestion::create([
                                'quiz_id' => $quiz->id,
                                'bank_question_id' => $bq->id,
                                'type' => $bq->type,
                                'body' => $bq->body,
                                'explanation' => $bq->explanation,
                                'score' => $bq->score,
                                'order' => $startOrder,
                            ]);
                            foreach ($bq->options as $opt) {
                                $qq->options()->create([
                                    'label' => $opt->label,
                                    'is_correct' => $opt->is_correct,
                                    'order' => $opt->order,
                                ]);
                            }
                        }

                        $quiz->update(['total_score' => $quiz->questions()->sum('score')]);

                        Notification::make()
                            ->title('Soal berhasil diimport')
                            ->body($bankQuestions->count() . ' soal ditambahkan ke kuis.')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->modifyQueryUsing(fn ($query) => $query)
            ->reorderable('order');
    }
}

