<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Latihan & Kuis</h1>
        <p class="text-sm text-slate-500">Kerjakan latihan soal interaktif dan lihat skor langsung.</p>
    </div>

    {{-- Filter tabs --}}
    <div class="flex flex-wrap gap-2 bg-white p-2 rounded-xl border border-slate-100 shadow-sm">
        @foreach(['all' => 'Semua', 'available' => 'Tersedia', 'finished' => 'Sudah Dikerjakan', 'closed' => 'Sudah Tutup'] as $key => $label)
            @php $isActive = $status === $key; @endphp
            <button type="button" wire:click="$set('status', '{{ $key }}')"
                    style="{{ $isActive ? 'background-color:#059669;color:#ffffff;' : 'background-color:transparent;color:#475569;' }}"
                    class="px-4 py-2 rounded-lg text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-emerald-300 {{ $isActive ? 'shadow-sm' : 'hover:bg-slate-100' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="space-y-3">
        @forelse($quizzes as $quiz)
            @php
                $attempts = $quiz->attempts;
                $bestScore = $attempts->whereNotNull('score')->max('score');
                $usedAttempts = $attempts->whereNotNull('submitted_at')->count();
                $remaining = max(0, $quiz->max_attempts - $usedAttempts);
                $inProgress = $attempts->firstWhere(fn ($a) => $a->started_at && ! $a->submitted_at);
                $isClosed = $quiz->closes_at && $quiz->closes_at->isPast();
                $notOpenYet = $quiz->opens_at && $quiz->opens_at->isFuture();
            @endphp
            <a href="{{ route('portal.quizzes.show', $quiz->slug) }}" class="block bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">{{ $quiz->subject?->name ?? 'Umum' }}</span>
                            <span class="px-2 py-0.5 rounded-md text-xs font-semibold {{ $quiz->scope === 'public' ? 'bg-sky-100 text-sky-700' : 'bg-violet-100 text-violet-700' }}">
                                {{ $quiz->scope === 'public' ? 'Publik' : ($quiz->schoolClass?->name ?? 'Kelas') }}
                            </span>
                            @if($inProgress)
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-xs font-semibold">Sedang Dikerjakan</span>
                            @elseif($bestScore !== null)
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-xs font-semibold">Skor Terbaik: {{ $bestScore }}/{{ $quiz->total_score }}</span>
                            @elseif($isClosed)
                                <span class="px-2 py-0.5 rounded-md bg-slate-200 text-slate-600 text-xs font-semibold">Tutup</span>
                            @elseif($notOpenYet)
                                <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-xs font-semibold">Belum Dibuka</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-xs font-semibold">Tersedia</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-800 text-base">{{ $quiz->title }}</h3>
                        <div class="text-xs text-slate-500 mt-1 flex flex-wrap gap-x-3 gap-y-1">
                            <span>📝 {{ $quiz->questions_count }} soal</span>
                            @if($quiz->duration_minutes)
                                <span>⏱️ {{ $quiz->duration_minutes }} menit</span>
                            @endif
                            <span>🎯 Total {{ $quiz->total_score }} poin</span>
                            <span>🔁 Sisa kesempatan: {{ $remaining }}/{{ $quiz->max_attempts }}</span>
                            @if($quiz->closes_at)
                                <span>📅 Tutup: {{ $quiz->closes_at->translatedFormat('d M Y, H:i') }}</span>
                            @endif
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-xl p-12 text-center border border-dashed border-slate-200">
                <p class="text-slate-500">Belum ada kuis untuk filter ini.</p>
            </div>
        @endforelse
    </div>

    @if($quizzes->hasPages())
        <div>{{ $quizzes->links() }}</div>
    @endif
</div>
