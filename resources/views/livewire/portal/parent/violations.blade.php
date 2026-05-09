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
            <div class="text-xs text-slate-500">Pelanggaran &middot; {{ $student->schoolClass?->name ?? '—' }}</div>
        </div>
        <div class="text-right">
            <div class="text-xs text-slate-500">Total Poin</div>
            <div class="text-2xl font-extrabold {{ $totalPoints > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $totalPoints }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
        @if($items->isEmpty())
            <div class="p-8 text-center text-sm text-slate-500">Tidak ada catatan pelanggaran. Pertahankan ya! 🎉</div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($items as $v)
                    <li class="p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $v->description }}</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">{{ $v->category_label }}</span>
                                <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 text-[11px] font-bold">+{{ $v->points }} poin</span>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">{{ $v->date->translatedFormat('d M Y') }} &middot; Pencatat: {{ $v->recorder?->name ?? '—' }}</div>
                            @if($v->action_taken)
                                <div class="text-xs text-slate-600 mt-1 italic">Tindakan: {{ $v->action_taken }}</div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
