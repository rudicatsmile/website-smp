<div>
    <x-site.page-hero key="kalender" />

    <x-site.page-frame>
        {{-- Stats Overview --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-slate-800">{{ $this->monthStats['total'] }}</div>
                        <div class="text-xs text-slate-500 font-medium uppercase">Total Acara</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-slate-800">{{ $this->monthStats['akademik'] }}</div>
                        <div class="text-xs text-slate-500 font-medium uppercase">Akademik</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-slate-800">{{ $this->monthStats['ekstrakurikuler'] }}</div>
                        <div class="text-xs text-slate-500 font-medium uppercase">Ekskul</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-slate-800">{{ $this->monthStats['libur'] }}</div>
                        <div class="text-xs text-slate-500 font-medium uppercase">Libur</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white rounded-2xl p-4 shadow-lg border border-slate-100 mb-8 flex flex-wrap items-center gap-3">
            <span class="text-sm font-bold text-slate-700 mr-2">Filter:</span>
            <button wire:click="$set('categoryFilter', 'all')" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $categoryFilter === 'all' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua</button>
            <button wire:click="$set('categoryFilter', 'umum')" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $categoryFilter === 'umum' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Umum</button>
            <button wire:click="$set('categoryFilter', 'akademik')" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $categoryFilter === 'akademik' ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Akademik</button>
            <button wire:click="$set('categoryFilter', 'ekstrakurikuler')" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $categoryFilter === 'ekstrakurikuler' ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-lg' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Ekstrakurikuler</button>
            <button wire:click="$set('categoryFilter', 'libur')" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $categoryFilter === 'libur' ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Libur</button>
            <div class="ml-auto">
                <button wire:click="goToToday" class="px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Hari Ini
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Calendar Grid - Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                    {{-- Month Navigation --}}
                    <div class="flex items-center justify-between px-8 py-6 bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-600 relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10 pointer-events-none">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
                            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full translate-y-1/2 -translate-x-1/2"></div>
                        </div>
                        <button wire:click="previousMonth" class="relative z-10 p-3 hover:bg-white/20 rounded-xl transition-all duration-300 text-white hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <div class="relative z-10 text-center">
                            <h2 class="text-3xl font-extrabold text-white tracking-tight">{{ $this->monthName }}</h2>
                            <p class="text-emerald-100 text-sm font-medium mt-1">Kalender Sekolah</p>
                        </div>
                        <button wire:click="nextMonth" class="relative z-10 p-3 hover:bg-white/20 rounded-xl transition-all duration-300 text-white hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    {{-- Days of Week Header --}}
                    <div class="grid grid-cols-7 bg-gradient-to-b from-slate-50 to-white border-b-2 border-slate-100">
                        <div class="p-4 text-center text-xs font-extrabold text-rose-600 tracking-widest">MIN</div>
                        <div class="p-4 text-center text-xs font-extrabold text-slate-700 tracking-widest">SEN</div>
                        <div class="p-4 text-center text-xs font-extrabold text-slate-700 tracking-widest">SEL</div>
                        <div class="p-4 text-center text-xs font-extrabold text-slate-700 tracking-widest">RAB</div>
                        <div class="p-4 text-center text-xs font-extrabold text-slate-700 tracking-widest">KAM</div>
                        <div class="p-4 text-center text-xs font-extrabold text-slate-700 tracking-widest">JUM</div>
                        <div class="p-4 text-center text-xs font-extrabold text-emerald-600 tracking-widest">SAB</div>
                    </div>

                    {{-- Calendar Days --}}
                    <div class="grid grid-cols-7 bg-slate-50">
                        @foreach ($this->calendarDays as $idx => $day)
                            @php $isSunday = $idx % 7 === 0; @endphp
                            @if ($day['isCurrentMonth'])
                                <button
                                    wire:click="selectDate({{ $day['day'] }})"
                                    class="relative min-h-[110px] p-3 border-b border-r border-slate-100 hover:bg-white hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300 group text-left {{ $day['isToday'] ? 'bg-gradient-to-br from-emerald-100 to-emerald-50 ring-2 ring-inset ring-emerald-400' : '' }} {{ $selectedDate === $day['dateStr'] ? 'bg-gradient-to-br from-emerald-200 to-emerald-100 ring-2 ring-inset ring-emerald-500' : '' }}"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="block text-sm font-extrabold {{ $day['isToday'] ? 'text-emerald-700' : ($isSunday ? 'text-rose-500' : 'text-slate-700 group-hover:text-emerald-600') }}">
                                            {{ $day['day'] }}
                                        </span>
                                        @if ($day['isToday'])
                                            <span class="text-[10px] font-bold bg-emerald-500 text-white px-1.5 py-0.5 rounded">HARI INI</span>
                                        @endif
                                    </div>
                                    @if ($day['hasEvents'])
                                        <div class="mt-2 space-y-1">
                                            @foreach ($events->where('event_date', $day['dateStr'])->take(2) as $event)
                                                @php
                                                    $eventColor = match(true) {
                                                        $event->is_holiday => 'from-amber-400 to-orange-500',
                                                        $event->category === 'akademik' => 'from-blue-500 to-indigo-600',
                                                        $event->category === 'ekstrakurikuler' => 'from-purple-500 to-pink-600',
                                                        default => 'from-emerald-500 to-teal-600',
                                                    };
                                                @endphp
                                                <div class="text-[11px] px-2 py-1 rounded-md truncate font-semibold shadow-sm bg-gradient-to-r {{ $eventColor }} text-white">
                                                    {{ $event->title }}
                                                </div>
                                            @endforeach
                                            @if ($events->where('event_date', $day['dateStr'])->count() > 2)
                                                <div class="text-[10px] font-bold text-slate-500 px-2">+{{ $events->where('event_date', $day['dateStr'])->count() - 2 }} lainnya</div>
                                            @endif
                                        </div>
                                    @endif
                                </button>
                            @else
                                <div class="min-h-[110px] p-3 border-b border-r border-slate-100 bg-slate-100/50"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Legend --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center gap-4">
                        <span class="text-xs font-bold text-slate-500 uppercase">Keterangan:</span>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                            <span class="text-xs text-slate-600 font-medium">Umum</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                            <span class="text-xs text-slate-600 font-medium">Akademik</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded bg-gradient-to-r from-purple-500 to-pink-600"></div>
                            <span class="text-xs text-slate-600 font-medium">Ekstrakurikuler</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded bg-gradient-to-r from-amber-400 to-orange-500"></div>
                            <span class="text-xs text-slate-600 font-medium">Libur</span>
                        </div>
                    </div>
                </div>

                {{-- Selected Date Events --}}
                @if ($selectedDate)
                    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
                        <h3 class="text-2xl font-extrabold text-slate-800 mb-6 flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span>{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</span>
                        </h3>
                        @if ($this->selectedDateEvents->isNotEmpty())
                            <div class="space-y-4">
                                @foreach ($this->selectedDateEvents as $event)
                                    @php
                                        $catColor = match(true) {
                                            $event->is_holiday => ['border' => 'border-amber-300', 'bg' => 'from-amber-50 to-orange-50', 'text' => 'text-amber-800', 'badge' => 'from-amber-400 to-orange-500'],
                                            $event->category === 'akademik' => ['border' => 'border-blue-200', 'bg' => 'from-blue-50 to-indigo-50', 'text' => 'text-blue-800', 'badge' => 'from-blue-500 to-indigo-600'],
                                            $event->category === 'ekstrakurikuler' => ['border' => 'border-purple-200', 'bg' => 'from-purple-50 to-pink-50', 'text' => 'text-purple-800', 'badge' => 'from-purple-500 to-pink-600'],
                                            default => ['border' => 'border-emerald-200', 'bg' => 'from-emerald-50 to-teal-50', 'text' => 'text-emerald-800', 'badge' => 'from-emerald-500 to-teal-600'],
                                        };
                                    @endphp
                                    <div class="p-6 rounded-2xl border-2 {{ $catColor['border'] }} bg-gradient-to-br {{ $catColor['bg'] }} shadow-md hover:shadow-xl transition-all">
                                        <div class="flex items-start justify-between flex-wrap gap-3">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r {{ $catColor['badge'] }} text-white shadow-sm">{{ $event->category }}</span>
                                                    @if ($event->is_holiday)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-500 text-white shadow-sm">LIBUR</span>
                                                    @endif
                                                </div>
                                                <h4 class="text-xl font-extrabold {{ $catColor['text'] }}">{{ $event->title }}</h4>
                                                @if ($event->description)
                                                    <p class="text-sm text-slate-700 mt-3 leading-relaxed">{{ $event->description }}</p>
                                                @endif
                                                @if ($event->location)
                                                    <p class="text-sm text-slate-600 mt-3 flex items-center gap-2 font-medium">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        {{ $event->location }}
                                                    </p>
                                                @endif
                                            </div>
                                            @if ($event->start_time)
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r {{ $catColor['badge'] }} text-white shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        {{ $event->start_time->format('H:i') }}{{ $event->end_time ? ' - ' . $event->end_time->format('H:i') : '' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 text-slate-500">
                                <div class="w-20 h-20 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-lg font-medium">Tidak ada acara pada tanggal ini.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Upcoming Events - Right Column --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sticky top-8">
                    <h3 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        Acara Mendatang
                    </h3>
                    @if ($this->upcomingEvents->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($this->upcomingEvents as $event)
                                @php
                                    $catBadge = match(true) {
                                        $event->is_holiday => 'from-amber-400 to-orange-500',
                                        $event->category === 'akademik' => 'from-blue-500 to-indigo-600',
                                        $event->category === 'ekstrakurikuler' => 'from-purple-500 to-pink-600',
                                        default => 'from-emerald-500 to-teal-600',
                                    };
                                @endphp
                                <div class="p-4 rounded-2xl border border-slate-100 bg-gradient-to-br from-white to-slate-50 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center bg-gradient-to-br {{ $catBadge }} shadow-md">
                                                <div class="text-xl font-extrabold text-white leading-none">{{ $event->event_date->format('d') }}</div>
                                                <div class="text-[10px] font-bold text-white/90 uppercase mt-0.5">{{ $event->event_date->translatedFormat('M') }}</div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-800 text-sm leading-tight line-clamp-2">{{ $event->title }}</h4>
                                            @if ($event->location)
                                                <p class="text-xs text-slate-500 mt-1.5 truncate flex items-center gap-1">
                                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    {{ $event->location }}
                                                </p>
                                            @endif
                                            @if ($event->start_time)
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-gradient-to-r {{ $catBadge }} text-white">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        {{ $event->start_time->format('H:i') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-slate-500">
                            <div class="w-16 h-16 mx-auto mb-3 bg-slate-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <p class="text-sm font-medium">Belum ada acara mendatang.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-site.page-frame>
</div>
