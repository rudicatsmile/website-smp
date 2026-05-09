<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('portal.announcements.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-emerald-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-3">
            @if($announcement->pinned)
                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-700 text-xs font-semibold">📌 Disematkan</span>
            @endif
            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs font-semibold">{{ $announcement->schoolClass?->name ?? 'GLOBAL' }}</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 leading-tight">{{ $announcement->title }}</h1>
        <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-slate-100 text-sm text-slate-600">
            <span>👤 {{ $announcement->teacher?->name ?? 'Admin' }}</span>
            <span>🗓️ {{ $announcement->published_at?->translatedFormat('d M Y, H:i') }}</span>
        </div>

        @if($announcement->body)
            <div class="prose prose-slate max-w-none mt-6">{!! $announcement->body !!}</div>
        @endif

        @if(is_array($announcement->attachments) && count($announcement->attachments))
            <div class="mt-6 pt-6 border-t border-slate-100">
                <h3 class="font-semibold text-slate-800 mb-3">Lampiran</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($announcement->attachments as $path)
                        @php $ext = strtoupper(pathinfo($path, PATHINFO_EXTENSION)); @endphp
                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($path) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">{{ $ext }}</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-slate-800 truncate">{{ basename($path) }}</div>
                                <div class="text-xs text-emerald-600 font-semibold">Unduh →</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
