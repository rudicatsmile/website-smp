<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-white/80 text-sm">Selamat datang kembali,</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $student->name }}</h1>
                <p class="text-white/90 mt-1">
                    Kelas <span class="font-semibold">{{ $student->schoolClass?->name ?? '—' }}</span>
                    @if($student->nis) &middot; NIS: {{ $student->nis }} @endif
                </p>
            </div>
            <div class="text-right text-white/90">
                <div class="text-xs">{{ now()->translatedFormat('l') }}</div>
                <div class="text-lg font-bold">{{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide">Tugas Aktif</div>
                    <div class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $openCount }}</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide">Terlambat</div>
                    <div class="text-3xl font-extrabold text-red-600 mt-1">{{ $overdueCount }}</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide">Sudah Dikumpulkan</div>
                    <div class="text-3xl font-extrabold text-sky-600 mt-1">{{ $submittedCount }}</div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Tugas Terbaru --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">Tugas Terbaru</h2>
                <a href="{{ route('portal.assignments.index') }}" class="text-sm text-emerald-600 font-medium hover:underline">Lihat semua &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($assignments as $a)
                    @php
                        $sub = $a->submissions->first();
                        $submitted = $sub && $sub->submitted_at;
                        $overdue = $a->is_overdue && !$submitted;
                        $dueSoon = $a->due_at && !$submitted && !$overdue && $a->due_at->diffInHours(now()) < 48;
                    @endphp
                    <a href="{{ route('portal.assignments.show', $a->slug) }}" class="block bg-white rounded-xl p-4 border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs font-semibold">{{ $a->subject?->name ?? 'Umum' }}</span>
                                    @if($submitted)
                                        <span class="px-2 py-0.5 rounded-md bg-sky-100 text-sky-700 text-xs font-semibold">Sudah Submit</span>
                                    @elseif($overdue)
                                        <span class="px-2 py-0.5 rounded-md bg-red-100 text-red-700 text-xs font-semibold">Terlambat</span>
                                    @elseif($dueSoon)
                                        <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-xs font-semibold">Segera</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-xs font-semibold">Aktif</span>
                                    @endif
                                </div>
                                <div class="font-semibold text-slate-800">{{ $a->title }}</div>
                                <div class="text-xs text-slate-500 mt-1">
                                    @if($a->due_at)
                                        Deadline: {{ $a->due_at->translatedFormat('d M Y, H:i') }}
                                    @else
                                        Tanpa deadline
                                    @endif
                                    @if($a->teacher) &middot; {{ $a->teacher->name }} @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-xl p-8 text-center border border-dashed border-slate-200">
                        <p class="text-slate-500">Belum ada tugas aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pengumuman --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">Pengumuman</h2>
                <a href="{{ route('portal.announcements.index') }}" class="text-sm text-emerald-600 font-medium hover:underline">Semua &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($announcements as $an)
                    <a href="{{ route('portal.announcements.show', $an->slug) }}" class="block bg-white rounded-xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center gap-2 mb-1">
                            @if($an->pinned)
                                <span class="text-amber-500" title="Disematkan">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 011-1h8a1 1 0 011 1v13l-5-3-5 3V4z"/></svg>
                                </span>
                            @endif
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-semibold">{{ $an->schoolClass?->name ?? 'GLOBAL' }}</span>
                        </div>
                        <div class="font-semibold text-slate-800 text-sm">{{ $an->title }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $an->published_at?->diffForHumans() }} &middot; {{ $an->teacher?->name ?? 'Admin' }}</div>
                    </a>
                @empty
                    <div class="bg-white rounded-xl p-6 text-center border border-dashed border-slate-200">
                        <p class="text-sm text-slate-500">Belum ada pengumuman.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
