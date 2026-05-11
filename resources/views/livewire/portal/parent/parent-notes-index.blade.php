<div class="space-y-5">
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm">
            <div class="flex items-center gap-2 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Berhasil
            </div>
            <div class="mt-1">{{ session('success') }}</div>
        </div>
    @endif

    <div class="flex items-center justify-between gap-3 flex-wrap">
        <a href="{{ route('portal.parent.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('portal.parent.notes.create', ['student' => $student->slug]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:scale-[1.02] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Topik Baru
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
        @if($student->photo_url)
            <img src="{{ $student->photo_url }}" class="w-12 h-12 rounded-2xl object-cover">
        @else
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-bold flex items-center justify-center">{{ mb_substr($student->name, 0, 1) }}</div>
        @endif
        <div class="flex-1">
            <div class="text-lg font-extrabold text-slate-800">{{ $student->name }}</div>
            <div class="text-xs text-slate-500">Buku Penghubung Digital &middot; {{ $student->schoolClass?->name ?? '—' }}</div>
        </div>
        <div class="text-right">
            <div class="text-xs text-slate-500">Total Topik</div>
            <div class="text-2xl font-extrabold text-emerald-600">{{ $notes->count() }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
        @if($notes->isEmpty())
            <div class="p-8 text-center text-sm text-slate-500">
                Belum ada topik komunikasi. Klik <span class="font-semibold text-emerald-600">"Topik Baru"</span> untuk memulai percakapan dengan wali kelas.
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($notes as $note)
                    @php
                        $statusColors = [
                            'open'     => 'bg-blue-100 text-blue-800',
                            'replied'  => 'bg-amber-100 text-amber-800',
                            'resolved' => 'bg-emerald-100 text-emerald-800',
                            'closed'   => 'bg-slate-200 text-slate-700',
                        ];
                        $categoryColors = [
                            'akademik'  => 'bg-blue-50 text-blue-700',
                            'perilaku'  => 'bg-rose-50 text-rose-700',
                            'kehadiran' => 'bg-amber-50 text-amber-700',
                            'kesehatan' => 'bg-pink-50 text-pink-700',
                            'lainnya'   => 'bg-slate-50 text-slate-700',
                        ];
                    @endphp
                    <li>
                        <a href="{{ route('portal.parent.notes.show', $note->id) }}" class="block p-4 hover:bg-slate-50 transition">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center relative">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    @if($note->unread_count > 0)
                                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center px-1">{{ $note->unread_count }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 flex-wrap">
                                        <div class="font-bold text-slate-800 truncate">{{ $note->subject }}</div>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $statusColors[$note->status] ?? 'bg-slate-100 text-slate-700' }}">{{ $note->status_label }}</span>
                                    </div>
                                    <div class="mt-1 flex items-center gap-2 text-xs text-slate-500 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-full font-semibold {{ $categoryColors[$note->category] ?? 'bg-slate-50 text-slate-700' }}">{{ $note->category_label }}</span>
                                        <span>&middot;</span>
                                        <span>Kode {{ $note->code }}</span>
                                        <span>&middot;</span>
                                        <span>{{ $note->messages_count }} pesan</span>
                                        <span>&middot;</span>
                                        <span>{{ $note->homeroomTeacher?->name ?? 'Wali Kelas' }}</span>
                                    </div>
                                    <div class="mt-1 text-xs text-slate-400">Aktivitas terakhir {{ $note->last_activity_at?->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
