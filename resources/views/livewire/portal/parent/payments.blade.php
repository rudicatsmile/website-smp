<div class="space-y-5">
    <div>
        <a href="{{ route('portal.parent.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Dashboard
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
        @if($student->photo_url)
            <img src="{{ $student->photo_url }}" class="w-12 h-12 rounded-2xl object-cover">
        @else
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold flex items-center justify-center">{{ mb_substr($student->name, 0, 1) }}</div>
        @endif
        <div class="flex-1">
            <div class="text-lg font-extrabold text-slate-800">{{ $student->name }}</div>
            <div class="text-xs text-slate-500">Pembayaran &middot; {{ $student->schoolClass?->name ?? '—' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
            <div class="text-xs font-bold text-amber-700 uppercase">Total Tunggakan</div>
            <div class="text-2xl font-extrabold text-amber-800 mt-1">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
            <div class="text-xs font-bold text-emerald-700 uppercase">Total Sudah Dibayar</div>
            <div class="text-2xl font-extrabold text-emerald-800 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
        @if($items->isEmpty())
            <div class="p-8 text-center text-sm text-slate-500">Belum ada tagihan.</div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($items as $p)
                    @php
                        $statusClass = match($p->status) {
                            'paid' => 'bg-emerald-100 text-emerald-700',
                            'unpaid' => 'bg-amber-100 text-amber-700',
                            'overdue' => 'bg-rose-100 text-rose-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <li class="p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl {{ $p->status === 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $p->type_label }} &middot; {{ $p->period }}</span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $statusClass }}">{{ $p->status_label }}</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                                Jatuh tempo: {{ $p->due_date?->translatedFormat('d M Y') ?? '—' }}
                                @if($p->paid_at)
                                    &middot; Dibayar: {{ $p->paid_at->translatedFormat('d M Y') }}
                                @endif
                            </div>
                            @if($p->note)
                                <div class="text-xs text-slate-600 mt-1 italic">{{ $p->note }}</div>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-extrabold text-slate-800">{{ $p->amount_formatted }}</div>
                            @if($p->status !== 'paid')
                                <button type="button" disabled class="mt-1 px-3 py-1 rounded-lg bg-slate-100 text-slate-400 text-xs font-semibold cursor-not-allowed" title="Bayar online akan hadir di update berikutnya">Bayar Online</button>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="text-xs text-slate-500 text-center">Pembayaran online akan tersedia di pembaruan berikutnya. Untuk saat ini, silakan bayar langsung ke kantor sekolah.</div>
</div>
