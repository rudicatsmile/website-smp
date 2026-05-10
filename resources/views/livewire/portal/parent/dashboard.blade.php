<div class="space-y-6">
    <div class="flex items-start justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau perkembangan putra/putri Anda di sini.</p>
        </div>
    </div>

    @if($children->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center">
            <p class="text-slate-600">Akun Anda belum terhubung ke data siswa. Hubungi administrator sekolah.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @foreach($children as $child)
                @php $s = $summary[$child->id]; @endphp
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-blue-50 flex items-center gap-4">
                        @if($child->photo_url)
                            <img src="{{ $child->photo_url }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-white shadow">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold text-xl flex items-center justify-center ring-2 ring-white shadow">
                                {{ mb_substr($child->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="text-lg font-extrabold text-slate-800 truncate">{{ $child->name }}</div>
                            <div class="text-xs text-slate-500">NIS {{ $child->nis }} &middot; {{ $child->schoolClass?->name ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-0 divide-x divide-slate-100 border-b border-slate-100">
                        <div class="p-4 text-center">
                            <div class="text-[11px] font-semibold text-slate-500 uppercase">Rata-rata Nilai</div>
                            <div class="text-xl font-extrabold text-emerald-600 mt-1">{{ $s['avg_grade'] ?? '—' }}</div>
                        </div>
                        <div class="p-4 text-center">
                            <div class="text-[11px] font-semibold text-slate-500 uppercase">Kehadiran</div>
                            <div class="text-xl font-extrabold text-blue-600 mt-1">{{ $s['attendance_pct'] }}%</div>
                        </div>
                        <div class="p-4 text-center">
                            <div class="text-[11px] font-semibold text-slate-500 uppercase">Poin Pelanggaran</div>
                            <div class="text-xl font-extrabold {{ $s['violation_points'] > 0 ? 'text-rose-600' : 'text-slate-400' }} mt-1">{{ $s['violation_points'] }}</div>
                        </div>
                        <div class="p-4 text-center">
                            <div class="text-[11px] font-semibold text-slate-500 uppercase">Tagihan</div>
                            <div class="text-xl font-extrabold {{ $s['unpaid_count'] > 0 ? 'text-amber-600' : 'text-emerald-600' }} mt-1">{{ $s['unpaid_count'] }}</div>
                        </div>
                    </div>

                    @if($s['unpaid_total'] > 0)
                        <div class="px-5 py-3 bg-amber-50 border-b border-amber-100 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
                            <span class="text-amber-800"><strong>Total tunggakan:</strong> Rp {{ number_format($s['unpaid_total'], 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="p-3 grid grid-cols-2 sm:grid-cols-5 gap-2">
                        <a href="{{ route('portal.parent.grades', $child->slug) }}" class="flex flex-col items-center gap-1 p-3 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition text-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            <span class="text-xs font-semibold text-slate-700">Nilai</span>
                        </a>
                        <a href="{{ route('portal.parent.attendance', $child->slug) }}" class="flex flex-col items-center gap-1 p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50/40 transition text-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-semibold text-slate-700">Absensi</span>
                        </a>
                        <a href="{{ route('portal.parent.violations', $child->slug) }}" class="flex flex-col items-center gap-1 p-3 rounded-xl border border-slate-200 hover:border-rose-300 hover:bg-rose-50/40 transition text-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z"/></svg>
                            <span class="text-xs font-semibold text-slate-700">Pelanggaran</span>
                        </a>
                        <a href="{{ route('portal.parent.payments', $child->slug) }}" class="flex flex-col items-center gap-1 p-3 rounded-xl border border-slate-200 hover:border-amber-300 hover:bg-amber-50/40 transition text-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs font-semibold text-slate-700">Pembayaran</span>
                        </a>
                        <a href="{{ route('portal.parent.leave.index', $child->slug) }}" class="flex flex-col items-center gap-1 p-3 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/40 transition text-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-xs font-semibold text-slate-700">Surat Izin</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
