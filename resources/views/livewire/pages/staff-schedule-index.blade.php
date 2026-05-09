@php
    // Display order: Senin, Selasa, ..., Minggu
    $orderedDays = [1, 2, 3, 4, 5, 6, 0];
    $colorMap = [
        'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'badge' => 'bg-emerald-600'],
        'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-800', 'badge' => 'bg-amber-600'],
        'sky' => ['bg' => 'bg-sky-50', 'border' => 'border-sky-200', 'text' => 'text-sky-800', 'badge' => 'bg-sky-600'],
        'slate' => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-800', 'badge' => 'bg-slate-600'],
        'rose' => ['bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'text' => 'text-rose-800', 'badge' => 'bg-rose-600'],
        'purple' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-800', 'badge' => 'bg-purple-600'],
        'teal' => ['bg' => 'bg-teal-50', 'border' => 'border-teal-200', 'text' => 'text-teal-800', 'badge' => 'bg-teal-600'],
    ];
@endphp
<div>
    <x-site.page-hero key="jadwal" title="Jadwal Mengajar & Piket" subtitle="Jadwal mingguan guru: mengajar, piket, dan rapat rutin." icon="heroicon-o-clock" />

    <x-site.page-frame>
        <div class="space-y-6">
            {{-- Filter Bar --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cari</label>
                        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Guru, mapel, kelas, lokasi..."
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Hari</label>
                        <select wire:model.live="day" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Semua</option>
                            @foreach($orderedDays as $d)
                                <option value="{{ $d }}">{{ $days[$d] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tipe</label>
                        <select wire:model.live="type" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Semua</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Guru</label>
                        <select wire:model.live="staffId" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Semua Guru</option>
                            @foreach($staffOptions as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <div class="inline-flex rounded-xl border border-slate-200 overflow-hidden">
                            <button wire:click="setView('grid')" class="px-3 py-2.5 text-xs font-semibold {{ $viewMode === 'grid' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-50' }}">Grid</button>
                            <button wire:click="setView('list')" class="px-3 py-2.5 text-xs font-semibold {{ $viewMode === 'list' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-50' }}">List</button>
                        </div>
                    </div>
                </div>
                @if($search || $day !== '' || $type || $staffId)
                    <div class="mt-3">
                        <button wire:click="clearFilters" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">&times; Bersihkan filter</button>
                    </div>
                @endif
            </div>

            @if($schedules->count() === 0)
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 mb-1">Tidak ada jadwal</h3>
                    <p class="text-sm text-slate-500">Coba ubah filter atau kata kunci.</p>
                </div>
            @elseif($viewMode === 'grid')
                {{-- GRID VIEW: Hari sebagai kolom --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <div class="min-w-[900px] grid grid-cols-7 divide-x divide-slate-100">
                            @foreach($orderedDays as $d)
                                @php $items = $byDay->get($d, collect())->sortBy('start_time'); @endphp
                                <div class="bg-slate-50/50">
                                    <div class="px-3 py-3 bg-gradient-to-br from-emerald-500 to-teal-600 text-white text-center">
                                        <div class="text-xs uppercase tracking-wide opacity-80">{{ $days[$d] }}</div>
                                        <div class="text-xs mt-0.5 opacity-80">{{ $items->count() }} jadwal</div>
                                    </div>
                                    <div class="p-2 space-y-2 min-h-[200px]">
                                        @forelse($items as $item)
                                            @php
                                                $c = $colorMap[$item->color] ?? $colorMap[$typeColors[$item->type] ?? 'emerald'] ?? $colorMap['emerald'];
                                            @endphp
                                            <div class="rounded-lg border {{ $c['border'] }} {{ $c['bg'] }} p-2.5">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-[10px] font-bold {{ $c['text'] }}">{{ $item->time_range }}</span>
                                                    <span class="px-1.5 py-0.5 rounded {{ $c['badge'] }} text-white text-[9px] font-bold uppercase">{{ $item->type_label }}</span>
                                                </div>
                                                <div class="text-xs font-bold text-slate-800 leading-tight mb-1">{{ $item->display_title }}</div>
                                                @if($item->staff)
                                                    <div class="text-[11px] text-slate-600 truncate">{{ $item->staff->name }}</div>
                                                @endif
                                                @if($item->location)
                                                    <div class="text-[10px] text-slate-500 truncate flex items-center gap-1">
                                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                        {{ $item->location }}
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="text-center text-xs text-slate-400 py-6">—</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{-- LIST VIEW --}}
                <div class="space-y-6">
                    @foreach($orderedDays as $d)
                        @php $items = $byDay->get($d, collect())->sortBy('start_time'); @endphp
                        @if($items->count() > 0)
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 mb-3 flex items-center gap-2">
                                    <span class="w-2 h-6 bg-emerald-600 rounded"></span>
                                    {{ $days[$d] }}
                                    <span class="text-sm text-slate-400 font-medium">({{ $items->count() }})</span>
                                </h3>
                                <div class="space-y-2">
                                    @foreach($items as $item)
                                        @php
                                            $c = $colorMap[$item->color] ?? $colorMap[$typeColors[$item->type] ?? 'emerald'] ?? $colorMap['emerald'];
                                        @endphp
                                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 flex flex-col md:flex-row md:items-center gap-3 md:gap-4">
                                            <div class="flex-shrink-0 md:w-32 text-center md:border-r md:pr-4 md:border-slate-100">
                                                <div class="text-base font-bold text-slate-800">{{ $item->time_range }}</div>
                                                <span class="inline-block mt-1 px-2 py-0.5 rounded {{ $c['badge'] }} text-white text-[10px] font-bold uppercase">{{ $item->type_label }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-semibold text-slate-800">{{ $item->display_title }}</div>
                                                @if($item->staff)
                                                    <a href="{{ route('staff.show', $item->staff->slug) }}" class="text-sm text-emerald-600 hover:underline">{{ $item->staff->name }}</a>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-500 md:text-right space-y-0.5">
                                                @if($item->location)
                                                    <div class="flex items-center gap-1 md:justify-end"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>{{ $item->location }}</div>
                                                @endif
                                                @if($item->period)
                                                    <div>{{ $item->period }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </x-site.page-frame>
</div>
