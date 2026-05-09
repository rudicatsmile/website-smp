<div class="space-y-6">
    <h1 class="text-2xl font-extrabold text-slate-800">Pengumuman Kelas</h1>
    <div class="space-y-3">
        @forelse($items as $an)
            <a href="{{ route('portal.announcements.show', $an->slug) }}" class="block bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            @if($an->pinned)
                                <span class="text-amber-500"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a1 1 0 011-1h8a1 1 0 011 1v13l-5-3-5 3V4z"/></svg></span>
                            @endif
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">{{ $an->schoolClass?->name ?? 'GLOBAL' }}</span>
                        </div>
                        <h3 class="font-bold text-slate-800">{{ $an->title }}</h3>
                        <div class="text-xs text-slate-500 mt-1">
                            {{ $an->published_at?->translatedFormat('d M Y, H:i') }} &middot; {{ $an->teacher?->name ?? 'Admin' }}
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-xl p-12 text-center border border-dashed border-slate-200">
                <p class="text-slate-500">Belum ada pengumuman.</p>
            </div>
        @endforelse
    </div>
    @if($items->hasPages()) <div>{{ $items->links() }}</div> @endif
</div>
