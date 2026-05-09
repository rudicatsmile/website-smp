<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Tugas Kelas {{ $student->schoolClass?->name }}</h1>
            <p class="text-sm text-slate-500">Lihat semua tugas, deadline, dan status pengumpulan.</p>
        </div>
    </div>

    {{-- Filter tabs --}}
    <div class="flex flex-wrap gap-2 bg-white p-2 rounded-xl border border-slate-100 shadow-sm">
        @foreach(['all' => 'Semua', 'pending' => 'Belum Dikerjakan', 'submitted' => 'Sudah Dikumpulkan', 'overdue' => 'Terlambat'] as $key => $label)
            @php $isActive = $status === $key; @endphp
            <button type="button" wire:click="$set('status', '{{ $key }}')"
                    style="{{ $isActive ? 'background-color:#059669;color:#ffffff;' : 'background-color:transparent;color:#475569;' }}"
                    class="px-4 py-2 rounded-lg text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-emerald-300 {{ $isActive ? 'shadow-sm' : 'hover:bg-slate-100' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="space-y-3">
        @forelse($assignments as $a)
            @php
                $sub = $a->submissions->first();
                $submitted = $sub && $sub->submitted_at;
                $graded = $sub && $sub->score !== null;
                $overdue = $a->is_overdue && !$submitted;
            @endphp
            <a href="{{ route('portal.assignments.show', $a->slug) }}" class="block bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">{{ $a->subject?->name ?? 'Umum' }}</span>
                            @if($graded)
                                <span class="px-2 py-0.5 rounded-md bg-sky-100 text-sky-700 text-xs font-semibold">Nilai: {{ $sub->score }}/{{ $a->max_score }}</span>
                            @elseif($submitted)
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-xs font-semibold">Sudah Submit</span>
                            @elseif($overdue)
                                <span class="px-2 py-0.5 rounded-md bg-red-100 text-red-700 text-xs font-semibold">Terlambat</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-xs font-semibold">Belum Dikerjakan</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-800 text-base">{{ $a->title }}</h3>
                        <div class="text-xs text-slate-500 mt-1 flex flex-wrap gap-x-3 gap-y-1">
                            @if($a->due_at)
                                <span>🗓️ Deadline: {{ $a->due_at->translatedFormat('d M Y, H:i') }}</span>
                            @endif
                            @if($a->teacher)
                                <span>👨‍🏫 {{ $a->teacher->name }}</span>
                            @endif
                            @if(is_array($a->attachments) && count($a->attachments))
                                <span>📎 {{ count($a->attachments) }} lampiran</span>
                            @endif
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-xl p-12 text-center border border-dashed border-slate-200">
                <p class="text-slate-500">Tidak ada tugas untuk filter ini.</p>
            </div>
        @endforelse
    </div>

    @if($assignments->hasPages())
        <div>{{ $assignments->links() }}</div>
    @endif
</div>
