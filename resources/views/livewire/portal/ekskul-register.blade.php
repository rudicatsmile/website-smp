<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center gap-4">
            <a href="{{ route('portal.ekskul.index') }}" wire:navigate
               class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="text-white/80 text-xs uppercase tracking-wide font-medium">Pendaftaran Ekstrakurikuler</p>
                <h1 class="text-xl font-extrabold mt-0.5">{{ $ekskul->name }}</h1>
            </div>
        </div>
    </div>

    {{-- Flash error --}}
    @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-lg mx-auto space-y-4">

        {{-- Info card ekskul --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center gap-4">
                {{-- Cover --}}
                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                    @if($ekskul->cover)
                        <img src="{{ $ekskul->cover_url }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Detail --}}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-800 text-base">{{ $ekskul->name }}</p>
                    @if($ekskul->coach)
                        <p class="text-xs text-slate-500 mt-0.5">
                            <span class="font-medium">Pembina:</span> {{ $ekskul->coach->name }}
                        </p>
                    @endif
                    @if($ekskul->location)
                        <p class="text-xs text-slate-500">📍 {{ $ekskul->location }}</p>
                    @endif
                </div>

                {{-- Quota badge --}}
                @if($ekskul->quota)
                    @php $approvedCount = $ekskul->members()->where('status', 'approved')->count(); @endphp
                    @php $pct = min(100, round($approvedCount / $ekskul->quota * 100)); @endphp
                    <div class="text-center shrink-0">
                        <div class="text-2xl font-extrabold {{ $pct >= 90 ? 'text-red-500' : 'text-emerald-600' }}">
                            {{ $approvedCount }}<span class="text-sm font-normal text-slate-400">/{{ $ekskul->quota }}</span>
                        </div>
                        <div class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold mt-0.5">Kuota</div>
                    </div>
                @endif
            </div>

            {{-- Jadwal mini --}}
            @if($ekskul->schedules->isNotEmpty())
                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-2">
                    @foreach($ekskul->schedules as $s)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 ring-1 ring-slate-200 text-xs text-slate-600 font-medium">
                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $s->day_name }}, {{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Form card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-base font-bold text-slate-800 mb-4">Form Pendaftaran</h2>

            <form wire:submit="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Motivasi Mendaftar
                        <span class="text-slate-400 font-normal ml-1">(opsional)</span>
                    </label>
                    <textarea wire:model="note" rows="4"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent resize-none transition"
                        placeholder="Ceritakan alasanmu ingin bergabung dengan ekskul ini..."></textarea>
                    @error('note')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-sm hover:shadow-emerald-200 hover:shadow-md disabled:opacity-60 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Kirim Pendaftaran
                    </span>
                    <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                        Mengirim...
                    </span>
                </button>
            </form>
        </div>

        {{-- Footer note --}}
        <p class="text-xs text-center text-slate-400 pb-2">
            Pendaftaran akan diverifikasi oleh pembina ekskul. Pantau status di halaman
            <a href="{{ route('portal.ekskul.index') }}" wire:navigate class="text-emerald-600 hover:underline font-medium">Ekstrakurikuler Saya</a>.
        </p>
    </div>

</div>
