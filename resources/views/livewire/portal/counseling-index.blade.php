<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Konseling BK</h1>
            <p class="text-sm text-slate-500">Ruang aman untuk bercerita dan berkonsultasi dengan Guru BK.</p>
        </div>
        <a href="{{ route('portal.counseling.create') }}"
           style="background-color:#059669;color:#ffffff;"
           class="px-4 py-2 rounded-lg text-sm font-bold shadow-sm">+ Buat Pengaduan Baru</a>
    </div>

    <div class="space-y-3">
        @forelse($tickets as $t)
            <a href="{{ route('portal.counseling.show', $t->id) }}" class="block bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold tracking-wider">{{ $t->code }}</span>
                            <span class="px-2 py-0.5 rounded-md text-xs font-bold
                                @if($t->status === 'new') bg-blue-100 text-blue-700
                                @elseif($t->status === 'in_progress') bg-amber-100 text-amber-700
                                @elseif($t->status === 'resolved') bg-emerald-100 text-emerald-700
                                @else bg-slate-200 text-slate-700 @endif">{{ $t->status_label }}</span>
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">{{ $t->category_label }}</span>
                        </div>
                        <div class="font-bold text-slate-800 truncate">{{ $t->subject }}</div>
                        <div class="text-xs text-slate-500 mt-1">
                            💬 {{ $t->messages_count }} pesan &middot; Aktivitas {{ $t->last_activity_at?->diffForHumans() }}
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-xl p-12 text-center border border-dashed border-slate-200">
                <p class="text-slate-500 mb-3">Belum ada tiket konseling.</p>
                <a href="{{ route('portal.counseling.create') }}" style="background-color:#059669;color:#ffffff;" class="inline-block px-4 py-2 rounded-lg text-sm font-bold">Buat Pengaduan Pertama</a>
            </div>
        @endforelse
    </div>

    @if($tickets->hasPages())
        <div>{{ $tickets->links() }}</div>
    @endif
</div>
