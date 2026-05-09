<div class="space-y-6">
    <a href="{{ route('portal.quizzes.show', $quiz->slug) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="rounded-2xl p-6 text-white shadow-lg" style="background:linear-gradient(135deg,#0d9488 0%,#0f766e 100%);">
        <div class="text-xs font-semibold uppercase tracking-wider opacity-90">Leaderboard</div>
        <h1 class="text-2xl font-extrabold mt-1">{{ $quiz->title }}</h1>
        <div class="text-sm opacity-90 mt-1">Top 20 berdasarkan skor terbaik</div>
        @if($myRank)
            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/20 text-sm font-bold">
                Peringkatmu: #{{ $myRank }}
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @forelse($rows as $i => $row)
            @php $rank = $i + 1; $isMe = $row->student_id === $currentStudentId; @endphp
            <div class="flex items-center gap-4 px-5 py-3 border-b border-slate-100 last:border-0 {{ $isMe ? 'bg-emerald-50/60' : '' }}">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm
                    {{ $rank === 1 ? 'bg-yellow-400 text-yellow-900' : ($rank === 2 ? 'bg-slate-300 text-slate-700' : ($rank === 3 ? 'bg-amber-300 text-amber-900' : 'bg-slate-100 text-slate-600')) }}">
                    {{ $rank }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-slate-800 truncate">{{ $row->student?->name ?? 'Siswa' }}{{ $isMe ? ' (Anda)' : '' }}</div>
                    <div class="text-xs text-slate-500">{{ $row->student?->schoolClass?->name ?? '—' }}</div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-extrabold text-emerald-700 tabular-nums">{{ $row->best_score }}<span class="text-sm text-slate-400">/{{ $quiz->total_score }}</span></div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-slate-500">Belum ada hasil yang masuk leaderboard.</div>
        @endforelse
    </div>
</div>
