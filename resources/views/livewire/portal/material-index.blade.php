<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Materi Kelas</h1>
            <p class="text-sm text-slate-500">Unduh materi pembelajaran dari guru.</p>
        </div>
        <select wire:model.live="subjectId" class="px-3 py-2 rounded-lg border border-slate-200 text-sm">
            <option value="">Semua Mapel</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($items as $m)
            <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-xs font-semibold">{{ $m->subject?->name ?? 'Umum' }}</span>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-xs">{{ $m->schoolClass?->name ?? 'Semua' }}</span>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">{{ $m->title }}</h3>
                @if($m->description)
                    <p class="text-sm text-slate-600 line-clamp-2 mb-3">{{ $m->description }}</p>
                @endif
                <div class="text-xs text-slate-500 mb-3">👨‍🏫 {{ $m->teacher?->name ?? '—' }}</div>
                @if($m->file_path)
                    <a href="{{ $m->file_url }}" target="_blank" class="mt-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Unduh Materi
                    </a>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl p-12 text-center border border-dashed border-slate-200">
                <p class="text-slate-500">Belum ada materi.</p>
            </div>
        @endforelse
    </div>
    @if($items->hasPages()) <div>{{ $items->links() }}</div> @endif
</div>
