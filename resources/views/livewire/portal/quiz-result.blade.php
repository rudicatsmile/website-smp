<div class="space-y-6">
    <a href="{{ route('portal.quizzes.show', $quiz->slug) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke detail kuis
    </a>

    {{-- Skor utama --}}
    <div class="rounded-2xl p-6 text-white shadow-lg" style="background:linear-gradient(135deg,#059669 0%,#0d9488 100%);">
        <div class="text-xs font-semibold uppercase tracking-wider opacity-90">Hasil Pengerjaan &middot; Attempt #{{ $attempt->attempt_no }}</div>
        <h1 class="text-2xl font-extrabold mt-1">{{ $quiz->title }}</h1>
        <div class="mt-4 flex flex-wrap items-end gap-6">
            <div>
                <div class="text-xs opacity-90">Skor</div>
                <div class="text-5xl font-black tabular-nums">
                    @if($attempt->is_graded || $attempt->score !== null)
                        {{ $attempt->score }}<span class="text-2xl opacity-80">/{{ $attempt->max_score }}</span>
                    @else
                        <span class="text-2xl">Menunggu Penilaian Essay</span>
                    @endif
                </div>
            </div>
            @if($attempt->is_graded && $attempt->max_score > 0)
                <div>
                    <div class="text-xs opacity-90">Nilai (skala 100)</div>
                    <div class="text-3xl font-black">{{ round(($attempt->score / max($attempt->max_score,1)) * 100) }}</div>
                </div>
            @endif
            <div>
                <div class="text-xs opacity-90">Waktu Pengerjaan</div>
                <div class="text-lg font-bold">
                    @if($attempt->started_at && $attempt->submitted_at)
                        {{ $attempt->started_at->diff($attempt->submitted_at)->format('%i mnt %s dtk') }}
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($quiz->show_explanation || $quiz->show_score_immediately)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
            <h2 class="text-base font-bold text-slate-800">Pembahasan Soal</h2>

            @foreach($attempt->answers->sortBy('question.order') as $i => $answer)
                @php
                    $q = $answer->question;
                    if (! $q) continue;
                    $opts = $q->options->keyBy('id');
                    $selected = $answer->selected_option_ids ?? [];
                    $correctIds = $q->options->where('is_correct', true)->pluck('id')->all();
                @endphp
                <div class="rounded-xl border {{ $answer->is_correct === null ? 'border-amber-200 bg-amber-50/30' : ($answer->is_correct ? 'border-emerald-200 bg-emerald-50/30' : 'border-red-200 bg-red-50/30') }} p-4">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="text-xs font-bold text-slate-500">Soal #{{ $loop->iteration }} &middot; {{ strtoupper($q->type) }}</div>
                        <div class="text-xs font-bold">
                            @if($answer->is_correct === true)
                                <span class="text-emerald-700">✓ Benar &middot; {{ $answer->score_awarded }}/{{ $q->score }}</span>
                            @elseif($answer->is_correct === false)
                                <span class="text-red-700">✗ Salah &middot; {{ $answer->score_awarded ?? 0 }}/{{ $q->score }}</span>
                            @else
                                <span class="text-amber-700">Menunggu Nilai &middot; max {{ $q->score }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-slate-800 mb-3">{!! nl2br(e(strip_tags($q->body))) !!}</div>

                    @if($q->type === 'essay')
                        <div class="text-xs font-semibold text-slate-500 mb-1">Jawaban Anda:</div>
                        <div class="rounded-lg bg-white border border-slate-200 p-3 text-slate-800 whitespace-pre-wrap">{{ $answer->essay_text ?: '—' }}</div>
                        @if($answer->feedback)
                            <div class="mt-2 text-xs font-semibold text-slate-500">Feedback Guru:</div>
                            <div class="mt-1 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-emerald-900 text-sm">{{ $answer->feedback }}</div>
                        @endif
                    @else
                        <div class="space-y-1.5">
                            @foreach($q->options as $opt)
                                @php
                                    $sel = in_array($opt->id, $selected, true);
                                    $correct = in_array($opt->id, $correctIds, true);
                                    $cls = 'border-slate-200 bg-white';
                                    if ($correct) $cls = 'border-emerald-400 bg-emerald-50';
                                    if ($sel && ! $correct) $cls = 'border-red-400 bg-red-50';
                                @endphp
                                <div class="rounded-lg border {{ $cls }} p-2.5 flex items-center gap-2 text-sm">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold
                                        {{ $correct ? 'bg-emerald-600 text-white' : ($sel ? 'bg-red-600 text-white' : 'bg-slate-200 text-slate-500') }}">
                                        {{ $sel ? '●' : '' }}
                                    </span>
                                    <span class="text-slate-800">{{ $opt->label }}</span>
                                    @if($correct)
                                        <span class="ml-auto text-xs font-bold text-emerald-700">Kunci</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($quiz->show_explanation && $q->explanation)
                        <div class="mt-3 rounded-lg bg-sky-50 border border-sky-200 p-3 text-sm text-sky-900">
                            <div class="text-xs font-bold uppercase tracking-wide mb-1">Pembahasan</div>
                            {!! nl2br(e(strip_tags($q->explanation))) !!}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('portal.quizzes.leaderboard', $quiz->slug) }}" class="px-4 py-2 rounded-lg text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50">Leaderboard</a>
        <a href="{{ route('portal.quizzes.show', $quiz->slug) }}" class="px-4 py-2 rounded-lg text-sm font-semibold" style="background-color:#059669;color:#ffffff;">Kembali</a>
    </div>
</div>
