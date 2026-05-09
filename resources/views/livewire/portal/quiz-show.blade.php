<div class="space-y-6">
    <a href="{{ route('portal.quizzes.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke daftar
    </a>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">{{ $quiz->subject?->name ?? 'Umum' }}</span>
            <span class="px-2 py-0.5 rounded-md text-xs font-semibold {{ $quiz->scope === 'public' ? 'bg-sky-100 text-sky-700' : 'bg-violet-100 text-violet-700' }}">
                {{ $quiz->scope === 'public' ? 'Publik' : ($quiz->schoolClass?->name ?? 'Kelas') }}
            </span>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-800">{{ $quiz->title }}</h1>
        @if($quiz->description)
            <div class="prose prose-sm max-w-none text-slate-700">{!! $quiz->description !!}</div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="rounded-xl bg-slate-50 p-3">
                <div class="text-xs text-slate-500">Jumlah Soal</div>
                <div class="text-lg font-extrabold text-slate-800">{{ $quiz->questions()->count() }}</div>
            </div>
            <div class="rounded-xl bg-slate-50 p-3">
                <div class="text-xs text-slate-500">Total Skor</div>
                <div class="text-lg font-extrabold text-slate-800">{{ $quiz->total_score }}</div>
            </div>
            <div class="rounded-xl bg-slate-50 p-3">
                <div class="text-xs text-slate-500">Durasi</div>
                <div class="text-lg font-extrabold text-slate-800">{{ $quiz->duration_minutes ? $quiz->duration_minutes.' mnt' : '∞' }}</div>
            </div>
            <div class="rounded-xl bg-slate-50 p-3">
                <div class="text-xs text-slate-500">Sisa Kesempatan</div>
                <div class="text-lg font-extrabold text-slate-800">{{ max(0, $quiz->max_attempts - $usedAttempts) }}/{{ $quiz->max_attempts }}</div>
            </div>
        </div>

        @if($quiz->opens_at || $quiz->closes_at)
            <div class="text-xs text-slate-500">
                @if($quiz->opens_at)Buka: {{ $quiz->opens_at->translatedFormat('d M Y H:i') }}@endif
                @if($quiz->closes_at) &middot; Tutup: {{ $quiz->closes_at->translatedFormat('d M Y H:i') }}@endif
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">{{ session('error') }}</div>
        @endif

        <div class="pt-2">
            @php $canStart = $quiz->is_open && $usedAttempts < $quiz->max_attempts; @endphp
            <button wire:click="start" type="button"
                    @disabled(! $canStart)
                    style="{{ $canStart ? 'background-color:#059669;color:#ffffff;' : 'background-color:#cbd5e1;color:#ffffff;cursor:not-allowed;' }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm">
                {{ $canStart ? 'Mulai Kerjakan' : 'Tidak Tersedia' }}
            </button>
            <a href="{{ route('portal.quizzes.leaderboard', $quiz->slug) }}" class="ml-2 px-4 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50">Leaderboard</a>
        </div>
    </div>

    @if($attempts->count())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-base font-bold text-slate-800 mb-3">Riwayat Pengerjaan</h2>
            <div class="divide-y divide-slate-100">
                @foreach($attempts as $a)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-slate-800">Attempt #{{ $a->attempt_no }}</div>
                            <div class="text-xs text-slate-500">
                                @if($a->submitted_at)
                                    Submit {{ $a->submitted_at->translatedFormat('d M Y H:i') }}
                                @elseif($a->started_at)
                                    Mulai {{ $a->started_at->translatedFormat('d M Y H:i') }} (belum submit)
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($a->is_graded)
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-xs font-bold">{{ $a->score }}/{{ $a->max_score }}</span>
                            @elseif($a->submitted_at)
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-xs font-bold">Menunggu Penilaian</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-xs font-bold">Berjalan</span>
                            @endif
                            @if($a->submitted_at)
                                <a href="{{ route('portal.quizzes.result', [$quiz->slug, $a->id]) }}" class="text-xs text-emerald-700 font-semibold hover:underline">Lihat Hasil</a>
                            @elseif($a->started_at)
                                <a href="{{ route('portal.quizzes.play', [$quiz->slug, $a->id]) }}" class="text-xs text-emerald-700 font-semibold hover:underline">Lanjutkan</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
