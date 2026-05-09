<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quizzes\RelationManagers;

use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'attempts';

    protected static ?string $title = 'Hasil Pengerjaan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Placeholder::make('info')->label('')
                ->content('Gunakan tombol "Nilai" untuk menilai jawaban essay siswa.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.name')->label('Siswa')->searchable(),
                TextColumn::make('student.schoolClass.name')->label('Kelas')->badge(),
                TextColumn::make('attempt_no')->label('Att')->badge(),
                TextColumn::make('submitted_at')->label('Submit')->dateTime('d M Y H:i')->placeholder('Belum'),
                TextColumn::make('score')->label('Skor')->badge()
                    ->formatStateUsing(fn ($state, $record) => $state === null ? '—' : $state . '/' . $record->max_score)
                    ->color(fn ($record) => $record->is_graded ? 'success' : 'warning'),
                TextColumn::make('status')->label('Status')->badge()
                    ->state(fn ($r) => match (true) {
                        ! $r->submitted_at && $r->started_at => 'berjalan',
                        $r->submitted_at && ! $r->is_graded => 'menunggu nilai',
                        $r->is_graded => 'selesai',
                        default => '—',
                    })
                    ->color(fn ($state) => match ($state) {
                        'selesai' => 'success',
                        'menunggu nilai' => 'warning',
                        'berjalan' => 'info',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->recordActions([
                Action::make('grade')
                    ->label('Nilai')
                    ->icon('heroicon-o-academic-cap')
                    ->color('primary')
                    ->visible(fn ($record) => (bool) $record->submitted_at)
                    ->schema(function ($record) {
                        $answers = $record->answers()->with('question.options')->get();
                        $components = [];
                        foreach ($answers as $answer) {
                            $q = $answer->question;
                            $type = $q->type;
                            $body = strip_tags($q->body);
                            if ($type === 'essay') {
                                $components[] = Placeholder::make('q_essay_'.$answer->id)
                                    ->label('Soal #'.$q->order.' (Essay, max '.$q->score.')')
                                    ->content(new HtmlString('<div class="text-slate-700">'.e($body).'</div>'.
                                        '<div class="mt-2 p-3 bg-slate-50 border rounded text-slate-800 whitespace-pre-wrap">'.e($answer->essay_text ?? '—').'</div>'));
                                $components[] = TextInput::make('grade_'.$answer->id)
                                    ->label('Skor')
                                    ->numeric()->minValue(0)->maxValue($q->score)
                                    ->default($answer->score_awarded);
                                $components[] = Textarea::make('feedback_'.$answer->id)
                                    ->label('Feedback')->rows(2)->default($answer->feedback);
                            } else {
                                $correct = $answer->is_correct ? '✅ Benar' : '❌ Salah';
                                $components[] = Placeholder::make('q_obj_'.$answer->id)
                                    ->label('Soal #'.$q->order.' ('.($type === 'mcq' ? 'PG' : 'Multi').')')
                                    ->content(new HtmlString('<div class="text-slate-700">'.e($body).' — <strong>'.$correct.' ('.$answer->score_awarded.'/'.$q->score.')</strong></div>'));
                            }
                        }
                        return $components ?: [Placeholder::make('empty')->content('Tidak ada jawaban.')];
                    })
                    ->action(function (array $data, $record) {
                        foreach ($record->answers as $answer) {
                            if ($answer->question->type !== 'essay') continue;
                            $score = $data['grade_'.$answer->id] ?? null;
                            $feedback = $data['feedback_'.$answer->id] ?? null;
                            if ($score !== null) {
                                $answer->update([
                                    'score_awarded' => (int) $score,
                                    'feedback' => $feedback,
                                    'is_correct' => $score > 0,
                                ]);
                            }
                        }
                        $totalScore = $record->answers()->sum('score_awarded');
                        $allGraded = $record->answers()->whereNull('score_awarded')->count() === 0;
                        $record->update([
                            'score' => $totalScore,
                            'is_graded' => $allGraded,
                            'graded_at' => $allGraded ? now() : $record->graded_at,
                            'graded_by' => $allGraded ? auth()->user()?->staffMember?->id : $record->graded_by,
                        ]);
                        Notification::make()->title('Penilaian disimpan')->success()->send();
                    }),
            ]);
    }
}
