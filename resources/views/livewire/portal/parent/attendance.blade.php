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
            <div class="text-xs text-slate-500">Absensi &middot; {{ $student->schoolClass?->name ?? '—' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs">
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3">
            <div class="text-[10px] font-bold uppercase text-emerald-700">Hadir</div>
            <div class="text-xl font-extrabold text-emerald-700 mt-1">{{ $counts['hadir'] }}</div>
        </div>
        <div class="rounded-xl bg-orange-50 border border-orange-200 p-3">
            <div class="text-[10px] font-bold uppercase text-orange-700">Terlambat</div>
            <div class="text-xl font-extrabold text-orange-700 mt-1">{{ $counts['terlambat'] }}</div>
        </div>
        <div class="rounded-xl bg-blue-50 border border-blue-200 p-3">
            <div class="text-[10px] font-bold uppercase text-blue-700">Izin</div>
            <div class="text-xl font-extrabold text-blue-700 mt-1">{{ $counts['izin'] }}</div>
        </div>
        <div class="rounded-xl bg-amber-50 border border-amber-200 p-3">
            <div class="text-[10px] font-bold uppercase text-amber-700">Sakit</div>
            <div class="text-xl font-extrabold text-amber-700 mt-1">{{ $counts['sakit'] }}</div>
        </div>
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-3">
            <div class="text-[10px] font-bold uppercase text-rose-700">Alpa</div>
            <div class="text-xl font-extrabold text-rose-700 mt-1">{{ $counts['alpa'] }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex items-center justify-between mb-4">
            <button wire:click="changeMonth(-1)" class="p-2 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="font-extrabold text-slate-800">{{ $monthLabel }}</div>
            <button wire:click="changeMonth(1)" class="p-2 rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <div class="grid grid-cols-7 gap-1 text-center">
            @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $d)
                <div class="text-[11px] font-bold text-slate-400 uppercase py-1">{{ $d }}</div>
            @endforeach
            @foreach($days as $cell)
                @if(! $cell)
                    <div></div>
                @else
                    @php
                        $rec = $cell['record'];
                        $bg = 'bg-slate-50 text-slate-400';
                        if ($rec) {
                            $bg = match($rec->status) {
                                'hadir' => 'bg-emerald-500 text-white',
                                'terlambat' => 'bg-orange-500 text-white',
                                'izin' => 'bg-blue-500 text-white',
                                'sakit' => 'bg-amber-500 text-white',
                                'alpa' => 'bg-rose-500 text-white',
                                default => 'bg-slate-200',
                            };
                        } elseif ($cell['is_weekend']) {
                            $bg = 'bg-slate-100 text-slate-400';
                        }
                    @endphp
                    <div class="aspect-square rounded-lg {{ $bg }} flex items-center justify-center text-sm font-semibold" title="{{ $rec?->status_label ?? '—' }}">
                        {{ $cell['day'] }}
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-center gap-3 text-[11px] text-slate-600">
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500"></span> Hadir</span>
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-orange-500"></span> Terlambat</span>
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-500"></span> Izin</span>
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-500"></span> Sakit</span>
            <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-rose-500"></span> Alpa</span>
        </div>
    </div>
</div>
