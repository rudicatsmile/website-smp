<div>
<x-site.page-hero
    key="alumni"
    title="Profil Alumni"
    subtitle="Kisah sukses alumni SMP Al Wathoniyah 9 yang menginspirasi"
    icon="academic-cap"
    :breadcrumbs="[['label' => 'Alumni']]"
/>

<x-site.page-frame :padded="true">
    {{-- Filters --}}
    <div class="mb-8 space-y-4">
        {{-- Status filter --}}
        <div class="flex flex-wrap gap-2">
            @foreach([
                null          => 'Semua',
                'working'     => 'Bekerja',
                'studying'    => 'Kuliah',
                'entrepreneur'=> 'Wirausaha',
                'both'        => 'Kuliah & Bekerja',
            ] as $val => $label)
                <button wire:click="setStatus({{ $val === null ? 'null' : "'{$val}'" }})"
                        class="px-4 py-2 rounded-full text-sm font-medium transition
                            {{ $status === $val
                                ? 'bg-emerald-600 text-white shadow-sm'
                                : 'bg-white border border-slate-200 text-slate-600 hover:border-emerald-400 hover:text-emerald-600' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Year filter --}}
        @if($years->isNotEmpty())
        <div class="flex flex-wrap gap-2">
            @foreach($years as $y)
                <button wire:click="setYear({{ $y }})"
                        class="px-3 py-1 rounded-full text-xs font-semibold transition
                            {{ $year === $y
                                ? 'bg-slate-800 text-white'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $y }}
                </button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Alumni grid --}}
    @if($alumni->isEmpty())
        <div class="py-20 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <p class="font-medium">Belum ada alumni yang tersedia.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($alumni as $a)
                <a href="{{ route('alumni.show', $a->slug) }}" wire:navigate
                   class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-emerald-200 transition overflow-hidden flex flex-col">

                    {{-- Photo --}}
                    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-emerald-50 to-teal-100">
                        @if($a->photo_url)
                            <img src="{{ $a->photo_url }}" alt="{{ $a->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="w-20 h-20 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            </div>
                        @endif

                        {{-- Year badge --}}
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full shadow">
                            Lulus {{ $a->graduation_year }}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start gap-2 mb-2">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide
                                {{ match($a->current_status) {
                                    'working'      => 'bg-emerald-100 text-emerald-700',
                                    'studying'     => 'bg-blue-100 text-blue-700',
                                    'entrepreneur' => 'bg-amber-100 text-amber-700',
                                    'both'         => 'bg-purple-100 text-purple-700',
                                    default        => 'bg-slate-100 text-slate-600',
                                } }}">
                                {{ $a->current_status_label }}
                            </span>
                        </div>

                        <h3 class="font-bold text-slate-800 text-base group-hover:text-emerald-700 transition">{{ $a->name }}</h3>

                        @if($a->position || $a->company_or_institution)
                            <p class="text-sm text-slate-500 mt-1 truncate">
                                {{ collect([$a->position, $a->company_or_institution])->filter()->implode(' @ ') }}
                            </p>
                        @endif

                        @if($a->quote)
                            <blockquote class="mt-3 text-sm text-slate-600 italic border-l-2 border-emerald-300 pl-3 line-clamp-2 flex-1">
                                "{{ $a->quote }}"
                            </blockquote>
                        @endif

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            @if($a->city)
                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $a->city }}
                                </span>
                            @else
                                <span></span>
                            @endif
                            <span class="text-xs text-emerald-600 font-medium group-hover:underline">Baca cerita →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- CTA Tracer Study --}}
    <div class="mt-16 bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-8 text-white text-center">
        <h2 class="text-2xl font-extrabold mb-2">Sudah alumni SMP Al Wathoniyah 9?</h2>
        <p class="text-emerald-100 mb-6">Bagikan perjalananmu dan bantu sekolah menjadi lebih baik melalui Tracer Study.</p>
        <a href="{{ route('tracer.form') }}" wire:navigate
           class="inline-flex items-center gap-2 bg-white text-emerald-700 hover:bg-emerald-50 font-bold px-6 py-3 rounded-xl transition shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Isi Tracer Study Sekarang
        </a>
    </div>
</x-site.page-frame>
</div>
