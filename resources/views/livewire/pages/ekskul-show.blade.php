<div>
    <x-site.page-hero
        key="ekskul"
        :title="$ekskul->name"
        :subtitle="$ekskul->coach ? 'Pembina: '.$ekskul->coach->name.($ekskul->location ? ' · 📍 '.$ekskul->location : '') : $ekskul->location"
        icon="user-group"
        :breadcrumbs="[['label' => 'Ekstrakurikuler', 'url' => route('ekskul.index')], ['label' => $ekskul->name]]"
    />

    <x-site.page-frame>
        {{-- Stats + CTA --}}
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <div class="bg-slate-50 rounded-xl ring-1 ring-slate-200 px-4 py-2 text-sm">
                <span class="font-bold text-emerald-600">{{ $ekskul->members->count() }}</span>
                <span class="text-slate-500 ml-1">Anggota</span>
            </div>
            @if($ekskul->quota)
            <div class="bg-slate-50 rounded-xl ring-1 ring-slate-200 px-4 py-2 text-sm">
                <span class="font-bold text-slate-700">{{ $ekskul->quota }}</span>
                <span class="text-slate-500 ml-1">Kuota</span>
            </div>
            @endif
            @if($ekskul->achievements->isNotEmpty())
            <div class="bg-slate-50 rounded-xl ring-1 ring-slate-200 px-4 py-2 text-sm">
                <span class="font-bold text-amber-500">{{ $ekskul->achievements->count() }}</span>
                <span class="text-slate-500 ml-1">Prestasi</span>
            </div>
            @endif
            <span class="px-3 py-1.5 rounded-full text-xs font-semibold
                {{ match($ekskul->category) {
                    'olahraga'  => 'bg-green-100 text-green-700',
                    'seni'      => 'bg-yellow-100 text-yellow-700',
                    'keagamaan' => 'bg-blue-100 text-blue-700',
                    'akademik'  => 'bg-purple-100 text-purple-700',
                    default     => 'bg-slate-100 text-slate-600',
                } }}">
                {{ match($ekskul->category) {
                    'olahraga'  => 'Olahraga',
                    'seni'      => 'Seni & Budaya',
                    'keagamaan' => 'Keagamaan',
                    'akademik'  => 'Akademik',
                    default     => 'Lainnya',
                } }}
            </span>
            <a href="{{ auth()->check() ? route('portal.ekskul.register', $ekskul) : route('portal.login') }}"
               wire:navigate
               class="ml-auto inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl text-sm font-semibold shadow transition">
                Daftar Sekarang
            </a>
        </div>

        {{-- Cover image --}}
        @if($ekskul->cover)
        <div class="mb-8 rounded-2xl overflow-hidden aspect-video shadow-md ring-1 ring-slate-200">
            <img src="{{ $ekskul->cover_url }}" alt="{{ $ekskul->name }}" class="w-full h-full object-cover">
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main column --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Deskripsi --}}
                @if($ekskul->description)
                <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-3">Tentang Ekskul</h2>
                    <div class="prose prose-sm max-w-none text-slate-600">{!! $ekskul->description !!}</div>
                </div>
                @endif

                {{-- Jadwal --}}
                @if($ekskul->schedules->isNotEmpty())
                <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-4">Jadwal Latihan</h2>
                    <div class="divide-y divide-slate-200">
                        @foreach($ekskul->schedules as $s)
                        <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
                            <span class="w-20 shrink-0 text-sm font-semibold text-emerald-600">{{ $s->day_name }}</span>
                            <span class="text-sm text-slate-700">{{ substr($s->start_time,0,5) }} – {{ substr($s->end_time,0,5) }}</span>
                            @if($s->location)
                                <span class="text-xs text-slate-400">📍 {{ $s->location }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Galeri --}}
                @if($ekskul->galleryItems->isNotEmpty())
                <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-800 mb-4">Galeri Kegiatan</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($ekskul->galleryItems as $item)
                        <div class="rounded-xl overflow-hidden aspect-square ring-1 ring-slate-200">
                            <img src="{{ $item->image_url }}" alt="{{ $item->caption }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Prestasi --}}
                @if($ekskul->achievements->isNotEmpty())
                <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200 p-5">
                    <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <x-heroicon-o-trophy class="w-5 h-5 text-amber-500" />
                        Prestasi
                    </h2>
                    <div class="space-y-4">
                        @foreach($ekskul->achievements as $ach)
                        <div class="flex gap-3">
                            @if($ach->cover)
                                <img src="{{ $ach->cover_url }}" class="w-10 h-10 rounded-lg object-cover shrink-0 ring-1 ring-slate-200">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-amber-50 ring-1 ring-amber-200 grid place-items-center shrink-0">
                                    <x-heroicon-o-trophy class="w-5 h-5 text-amber-500" />
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-slate-800 leading-tight">{{ $ach->title }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $ach->rank }} · {{ ucfirst($ach->level) }}</p>
                                <p class="text-xs text-slate-400">{{ $ach->achieved_at->translatedFormat('M Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Anggota --}}
                @if($ekskul->members->isNotEmpty())
                <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200 p-5">
                    <h2 class="text-base font-bold text-slate-800 mb-4">
                        Anggota
                        <span class="ml-1 text-sm font-normal text-slate-400">({{ $ekskul->members->count() }})</span>
                    </h2>
                    <div class="space-y-3">
                        @foreach($ekskul->members->take(10) as $m)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 grid place-items-center text-emerald-700 font-bold text-sm shrink-0">
                                {{ mb_substr($m->student->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 leading-tight">{{ $m->student->name }}</p>
                                <p class="text-xs text-slate-400">{{ $m->student->schoolClass?->name }}</p>
                            </div>
                        </div>
                        @endforeach
                        @if($ekskul->members->count() > 10)
                            <p class="text-xs text-slate-400 text-center pt-1">+{{ $ekskul->members->count() - 10 }} anggota lainnya</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-200">
            <a href="{{ route('ekskul.index') }}" wire:navigate
               class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 hover:text-emerald-800 transition">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali ke daftar ekskul
            </a>
        </div>
    </x-site.page-frame>
</div>
