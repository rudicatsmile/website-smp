<div class="bg-gradient-to-b from-indigo-50/50 via-white to-white">
    <div class="max-w-3xl mx-auto px-4 py-12 sm:py-16 space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-slate-800">Cek Status Surat Izin</h1>
            <p class="text-slate-600 mt-2">Masukkan NIS siswa untuk melihat riwayat & status pengajuan izin.</p>
            <a href="{{ route('izin.form') }}" class="inline-flex items-center gap-1 mt-3 text-sm font-semibold text-indigo-600 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajukan Izin Baru
            </a>
        </div>

        @if(session('leave_success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm">
                <div class="flex items-center gap-2 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pengajuan Berhasil Dikirim
                </div>
                <div class="mt-1">{{ session('leave_success') }}</div>
            </div>
        @endif

        <form wire:submit.prevent="search" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col sm:flex-row gap-3">
            <input type="text" wire:model="nis" placeholder="NIS Siswa"
                class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
            <button type="submit"
                style="background-color:#4f46e5;color:#ffffff;"
                class="px-5 py-2.5 rounded-xl text-sm font-bold">Cari</button>
        </form>

        @if($notFound)
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-700 text-sm">{{ $notFound }}</div>
        @endif

        @if($student)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
                @if($student->photo_url)
                    <img src="{{ $student->photo_url }}" class="w-12 h-12 rounded-2xl object-cover">
                @else
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold flex items-center justify-center">{{ mb_substr($student->name, 0, 1) }}</div>
                @endif
                <div class="flex-1">
                    <div class="text-lg font-extrabold text-slate-800">{{ $student->name }}</div>
                    <div class="text-xs text-slate-500">NIS {{ $student->nis }} &middot; {{ $student->schoolClass?->name ?? '—' }}</div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                @if($items->isEmpty())
                    <div class="p-8 text-center text-sm text-slate-500">Belum ada riwayat pengajuan izin untuk siswa ini.</div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach($items as $req)
                            @php
                                $statusColors = [
                                    'pending'  => 'bg-amber-100 text-amber-800',
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'rejected' => 'bg-rose-100 text-rose-800',
                                ];
                            @endphp
                            <li class="p-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-bold text-slate-800">#{{ $req->id }}</span>
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">{{ $req->type_label }}</span>
                                    <span class="px-2 py-0.5 rounded {{ $statusColors[$req->status] ?? 'bg-slate-100 text-slate-700' }} text-[11px] font-bold uppercase">{{ $req->status_label }}</span>
                                </div>
                                <div class="text-sm font-semibold text-slate-700 mt-1">{{ $req->date_range_label }} @if($req->day_count > 1) <span class="text-slate-400 font-normal">({{ $req->day_count }} hari)</span>@endif</div>
                                <div class="text-sm text-slate-600 mt-1 line-clamp-2">{{ $req->reason }}</div>
                                <div class="text-xs text-slate-400 mt-1.5">Diajukan {{ $req->created_at->translatedFormat('d M Y H:i') }}</div>
                                @if($req->review_note)
                                    <div class="mt-2 p-2.5 rounded-lg bg-slate-50 border border-slate-100 text-xs text-slate-700 italic">
                                        <span class="font-semibold not-italic">Catatan sekolah:</span> {{ $req->review_note }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
    </div>
</div>
