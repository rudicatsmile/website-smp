<div class="space-y-6">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="rounded-xl bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('info') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-white/80 text-sm">Portal Siswa</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold">Ekstrakurikuler</h1>
                <p class="text-white/90 mt-1 text-sm">Kelola pendaftaran dan lihat status keanggotaan ekskul.</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5m6 0v-4m0 0a4 4 0 10-8 0m8 0H9"/></svg>
            </div>
        </div>
    </div>

    @if(! $isStudent)
        <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            Akun ini tidak terhubung ke data siswa. Pendaftaran ekskul hanya dapat dilakukan oleh siswa.
        </div>
    @endif

    {{-- Pendaftaran Saya --}}
    <div>
        <h2 class="text-lg font-bold text-slate-800 mb-3">Pendaftaran Saya</h2>

        @if($myMemberships->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-8 text-center">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-slate-500 text-sm">Belum ada ekskul yang didaftarkan.</p>
                <p class="text-slate-400 text-xs mt-1">Pilih ekskul di bawah untuk mulai mendaftar.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($myMemberships as $m)
                    @php
                        $statusColor = match($m->status) {
                            'approved' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                            'rejected' => 'bg-red-100 text-red-700 ring-red-200',
                            default    => 'bg-amber-100 text-amber-700 ring-amber-200',
                        };
                        $statusLabel = match($m->status) {
                            'approved' => 'Diterima',
                            'rejected' => 'Ditolak',
                            default    => 'Menunggu',
                        };
                        $statusIcon = match($m->status) {
                            'approved' => 'M5 13l4 4L19 7',
                            'rejected' => 'M6 18L18 6M6 6l12 12',
                            default    => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-4 hover:border-emerald-200 hover:shadow-md transition">
                        {{-- Cover --}}
                        <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0">
                            @if($m->extracurricular->cover)
                                <img src="{{ $m->extracurricular->cover_url }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5"/></svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 truncate">{{ $m->extracurricular->name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Didaftarkan {{ $m->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Status badge --}}
                        <span class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold ring-1 {{ $statusColor }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $statusIcon }}"/></svg>
                            {{ $statusLabel }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Ekskul Tersedia --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold text-slate-800">Ekskul Tersedia</h2>
            <a href="{{ route('ekskul.index') }}" wire:navigate
               class="text-sm text-emerald-600 font-medium hover:underline">
                Lihat semua &rarr;
            </a>
        </div>

        @if($available->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-8 text-center">
                <p class="text-slate-500 text-sm">Kamu sudah mendaftar semua ekskul yang tersedia. 🎉</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($available as $ekskul)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-4 hover:border-emerald-200 hover:shadow-md transition group">
                        {{-- Cover --}}
                        <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0">
                            @if($ekskul->cover)
                                <img src="{{ $ekskul->cover_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-teal-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5"/></svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 truncate text-sm">{{ $ekskul->name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $ekskul->members_count }} anggota
                                @if($ekskul->quota)
                                    <span class="text-slate-300">·</span>
                                    Kuota {{ $ekskul->quota }}
                                @endif
                            </p>
                        </div>

                        {{-- Daftar button --}}
                        <a href="{{ route('portal.ekskul.register', $ekskul) }}" wire:navigate
                           class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl transition shadow-sm hover:shadow-emerald-200 hover:shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Daftar
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
