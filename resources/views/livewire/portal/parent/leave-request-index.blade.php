<div class="space-y-5">
    @if(session('leave_success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm">
            <div class="flex items-center gap-2 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Pengajuan Berhasil Dikirim
            </div>
            <div class="mt-1">{{ session('leave_success') }}</div>
        </div>
    @endif

    <div class="flex items-center justify-between gap-3 flex-wrap">
        <a href="{{ route('portal.parent.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('portal.parent.leave.create', ['student' => $student->slug]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-500/30 hover:scale-[1.02] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Izin Baru
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
            <div class="text-xs text-slate-500">Surat Izin &middot; {{ $student->schoolClass?->name ?? '—' }}</div>
        </div>
        <div class="text-right">
            <div class="text-xs text-slate-500">Total Pengajuan</div>
            <div class="text-2xl font-extrabold text-indigo-600">{{ $items->count() }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
        @if($items->isEmpty())
            <div class="p-8 text-center text-sm text-slate-500">
                Belum ada pengajuan izin. Klik tombol <span class="font-semibold text-indigo-600">"Ajukan Izin Baru"</span> di atas untuk memulai.
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($items as $req)
                    @php
                        $statusColors = [
                            'pending'  => 'bg-amber-100 text-amber-800',
                            'approved' => 'bg-emerald-100 text-emerald-800',
                            'rejected' => 'bg-rose-100 text-rose-800',
                        ];
                        $typeColors = [
                            'sakit' => 'bg-amber-50 text-amber-700',
                            'izin'  => 'bg-blue-50 text-blue-700',
                            'dinas' => 'bg-violet-50 text-violet-700',
                        ];
                    @endphp
                    <li class="p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $req->date_range_label }}</span>
                                <span class="px-2 py-0.5 rounded {{ $typeColors[$req->type] ?? 'bg-slate-100 text-slate-700' }} text-[11px] font-bold">{{ $req->type_label }}</span>
                                <span class="px-2 py-0.5 rounded {{ $statusColors[$req->status] ?? 'bg-slate-100 text-slate-700' }} text-[11px] font-bold uppercase">{{ $req->status_label }}</span>
                                @if($req->day_count > 1)
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-semibold">{{ $req->day_count }} hari</span>
                                @endif
                            </div>
                            <div class="text-sm text-slate-600 mt-1.5 line-clamp-2">{{ $req->reason }}</div>
                            <div class="text-xs text-slate-400 mt-1.5 flex flex-wrap items-center gap-3">
                                <span>Diajukan {{ $req->created_at->translatedFormat('d M Y H:i') }}</span>
                                @if($req->attachment_url)
                                    <a href="{{ $req->attachment_url }}" target="_blank" class="text-indigo-600 hover:underline font-semibold">📎 Lampiran</a>
                                @endif
                                @if($req->reviewed_at)
                                    <span>Direview {{ $req->reviewed_at->translatedFormat('d M Y H:i') }} oleh {{ $req->reviewer?->name ?? 'Admin' }}</span>
                                @endif
                            </div>
                            @if($req->review_note)
                                <div class="mt-2 p-2.5 rounded-lg bg-slate-50 border border-slate-100 text-xs text-slate-700 italic">
                                    <span class="font-semibold not-italic">Catatan dari sekolah:</span> {{ $req->review_note }}
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
