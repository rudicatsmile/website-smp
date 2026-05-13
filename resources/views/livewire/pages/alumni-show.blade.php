<div>
<x-site.page-hero
    key="alumni"
    :title="$alumni->name"
    :subtitle="collect([$alumni->position, $alumni->company_or_institution])->filter()->implode(' @ ')"
    icon="academic-cap"
    :breadcrumbs="[['label' => 'Alumni', 'url' => route('alumni.index')], ['label' => $alumni->name]]"
/>

<x-site.page-frame :padded="true">
    <div class="max-w-3xl mx-auto">

        {{-- Profile card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8 flex flex-col sm:flex-row gap-6 items-start">
            {{-- Photo --}}
            <div class="w-28 h-28 rounded-2xl overflow-hidden shrink-0 bg-gradient-to-br from-emerald-50 to-teal-100">
                @if($alumni->photo_url)
                    <img src="{{ $alumni->photo_url }}" alt="{{ $alumni->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center flex-wrap gap-2 mb-2">
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">
                        Lulus {{ $alumni->graduation_year }}
                    </span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full
                        {{ match($alumni->current_status) {
                            'working'      => 'bg-emerald-50 text-emerald-700',
                            'studying'     => 'bg-blue-50 text-blue-700',
                            'entrepreneur' => 'bg-amber-50 text-amber-700',
                            'both'         => 'bg-purple-50 text-purple-700',
                            default        => 'bg-slate-100 text-slate-600',
                        } }}">
                        {{ $alumni->current_status_label }}
                    </span>
                </div>

                <h1 class="text-2xl font-extrabold text-slate-800 mb-1">{{ $alumni->name }}</h1>

                @if($alumni->position || $alumni->company_or_institution)
                    <p class="text-slate-600">
                        {{ collect([$alumni->position, $alumni->company_or_institution])->filter()->implode(' — ') }}
                    </p>
                @endif

                @if($alumni->city)
                    <p class="text-slate-500 text-sm mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $alumni->city }}
                    </p>
                @endif

                @if($alumni->linkedin_url)
                    <a href="{{ $alumni->linkedin_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 mt-3 text-sm text-blue-600 hover:underline font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                        LinkedIn
                    </a>
                @endif
            </div>
        </div>

        {{-- Quote --}}
        @if($alumni->quote)
            <blockquote class="bg-emerald-50 border-l-4 border-emerald-500 rounded-xl px-6 py-4 mb-8">
                <p class="text-emerald-800 text-lg italic font-medium">"{{ $alumni->quote }}"</p>
            </blockquote>
        @endif

        {{-- Story --}}
        @if($alumni->story)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Perjalanan & Cerita Sukses
                </h2>
                <div class="prose prose-slate prose-sm max-w-none">
                    {!! $alumni->story !!}
                </div>
            </div>
        @endif

        {{-- Back + CTA --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <a href="{{ route('alumni.index') }}" wire:navigate
               class="inline-flex items-center gap-2 text-slate-600 hover:text-emerald-600 text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke daftar alumni
            </a>
            <a href="{{ route('tracer.form') }}" wire:navigate
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Isi Tracer Study
            </a>
        </div>
    </div>
</x-site.page-frame>
</div>
