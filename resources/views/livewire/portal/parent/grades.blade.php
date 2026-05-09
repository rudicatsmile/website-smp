<div class="space-y-5">
    <div class="flex items-center gap-3">
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
            <div class="text-xs text-slate-500">Nilai Rapor &middot; {{ $student->schoolClass?->name ?? '—' }}</div>
        </div>
        @if($avg)
            <div class="text-right">
                <div class="text-xs text-slate-500">Rata-rata</div>
                <div class="text-2xl font-extrabold text-emerald-600">{{ $avg }}</div>
            </div>
        @endif
    </div>

    <div class="flex items-center gap-3 flex-wrap">
        <select wire:model.live="academic_year" class="px-3 py-2 rounded-lg border-2 border-slate-300 bg-white text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
            @foreach($years as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
            @if($years->isEmpty())
                <option value="2025/2026">2025/2026</option>
            @endif
        </select>
        <div class="inline-flex rounded-lg border-2 border-slate-300 bg-white overflow-hidden">
            <button wire:click="$set('semester','ganjil')" class="px-4 py-2 text-sm font-semibold {{ $semester === 'ganjil' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-50' }}">Ganjil</button>
            <button wire:click="$set('semester','genap')" class="px-4 py-2 text-sm font-semibold {{ $semester === 'genap' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-50' }}">Genap</button>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
        @if($grades->isEmpty())
            <div class="p-8 text-center text-slate-500 text-sm">Belum ada data nilai untuk periode ini.</div>
        @else
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="text-left px-4 py-3">Mata Pelajaran</th>
                        <th class="text-center px-3 py-3">Tugas</th>
                        <th class="text-center px-3 py-3">UTS</th>
                        <th class="text-center px-3 py-3">UAS</th>
                        <th class="text-center px-3 py-3">Akhir</th>
                        <th class="text-center px-3 py-3">Predikat</th>
                        <th class="text-left px-4 py-3">Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($grades as $g)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $g->subject }}</td>
                            <td class="text-center px-3 py-3 text-slate-600">{{ $g->nilai_tugas ?? '—' }}</td>
                            <td class="text-center px-3 py-3 text-slate-600">{{ $g->nilai_uts ?? '—' }}</td>
                            <td class="text-center px-3 py-3 text-slate-600">{{ $g->nilai_uas ?? '—' }}</td>
                            <td class="text-center px-3 py-3 font-bold text-emerald-700">{{ $g->nilai_akhir ?? '—' }}</td>
                            <td class="text-center px-3 py-3">
                                @if($g->predikat)
                                    @php
                                        $c = match($g->predikat) {
                                            'A' => 'bg-emerald-100 text-emerald-700',
                                            'B' => 'bg-blue-100 text-blue-700',
                                            'C' => 'bg-amber-100 text-amber-700',
                                            default => 'bg-rose-100 text-rose-700',
                                        };
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-bold {{ $c }}">{{ $g->predikat }}</span>
                                @else — @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $g->teacher?->name ?? '—' }}</td>
                        </tr>
                        @if($g->catatan_guru)
                            <tr>
                                <td colspan="7" class="px-4 pb-3 pt-0">
                                    <div class="text-xs text-slate-600 italic bg-slate-50 rounded px-3 py-2"><strong>Catatan:</strong> {{ $g->catatan_guru }}</div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
