@props([
    'key' => null,
    'title' => null,
    'subtitle' => null,
    'breadcrumbs' => [],
    'icon' => null,
])

@php
    $hero = $key ? \App\Models\PageHero::forKey($key) : null;

    $heroTitle = $hero?->title ?: $title;
    $heroSubtitle = $hero?->subtitle ?: $subtitle;
    $heroIcon = $hero?->icon ?: $icon;
    $heroBg = $hero?->background_image;
    $showBreadcrumb = $hero ? $hero->show_breadcrumb : true;

    $from = $hero?->overlay_from ?: 'emerald-600';
    $via = $hero?->overlay_via ?: 'emerald-700';
    $to = $hero?->overlay_to ?: 'teal-800';
    $opacity = $hero?->overlay_opacity ?? 100;

    $gradientClasses = sprintf(
        'bg-gradient-to-br from-%s %sto-%s',
        $from,
        $via ? "via-{$via} " : '',
        $to,
    );
@endphp

<section class="relative overflow-hidden -mt-16 pt-28 pb-20 bg-slate-900">
    {{-- Background image (optional) --}}
    @if($heroBg)
        <img src="{{ asset('storage/'.$heroBg) }}"
             alt=""
             class="absolute inset-0 w-full h-full object-cover"
             aria-hidden="true">
    @endif

    {{-- Gradient overlay --}}
    <div class="absolute inset-0 {{ $gradientClasses }}"
         style="opacity: {{ max(0, min(100, (int) $opacity)) / 100 }}"
         aria-hidden="true"></div>

    {{-- Decorative pattern --}}
    <div class="absolute inset-0 opacity-10" aria-hidden="true"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl" aria-hidden="true"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-white/10 blur-3xl" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($showBreadcrumb && !empty($breadcrumbs))
            <nav class="flex items-center gap-2 text-sm text-white/80 mb-4" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                @foreach($breadcrumbs as $crumb)
                    <span class="text-white/40">/</span>
                    @if(!empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}" class="hover:text-white transition">{{ $crumb['label'] }}</a>
                    @else
                        <span class="text-white font-medium">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        <div class="flex items-start gap-5">
            @if($heroIcon)
                <div class="hidden sm:flex shrink-0 w-16 h-16 rounded-2xl bg-white/10 backdrop-blur ring-1 ring-white/20 items-center justify-center">
                    <x-dynamic-component :component="'heroicon-o-'.$heroIcon" class="w-9 h-9 text-white" />
                </div>
            @endif
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white drop-shadow">{{ $heroTitle }}</h1>
                @if($heroSubtitle)
                    <p class="mt-3 text-white/90 text-base sm:text-lg max-w-2xl">{{ $heroSubtitle }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Bottom wave decoration --}}
    <div class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-b from-transparent to-slate-50/40 pointer-events-none" aria-hidden="true"></div>
</section>
