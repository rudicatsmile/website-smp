{{-- Milleneal skin: page-hero --}}
@php
    $hero = $key ? \App\Models\PageHero::forKey($key) : null;

    $heroTitle = $hero?->title ?: $title;
    $heroSubtitle = $hero?->subtitle ?: $subtitle;
    $heroIcon = $hero?->icon ?: $icon;
    $heroBg = $hero?->background_image;
    $showBreadcrumb = $hero ? $hero->show_breadcrumb : true;

    $opacity = $hero?->overlay_opacity ?? 100;
@endphp

<section class="relative overflow-hidden -mt-16 pt-32 pb-24 bg-gradient-to-br from-pink-500 via-purple-600 to-indigo-700">
    {{-- Background image (optional) --}}
    @if($heroBg)
        <img src="{{ asset('storage/'.$heroBg) }}" alt="" class="absolute inset-0 w-full h-full object-cover" aria-hidden="true">
    @endif

    {{-- Vibrant overlay --}}
    <div class="absolute inset-0 bg-gradient-to-br from-pink-500 via-purple-600 to-indigo-700"
         style="opacity: {{ max(0, min(100, (int) $opacity)) / 100 }}"
         aria-hidden="true"></div>

    {{-- Decorative blobs --}}
    <div class="absolute top-20 left-10 w-72 h-72 bg-yellow-300/30 rounded-full blur-3xl animate-pulse" aria-hidden="true"></div>
    <div class="absolute -bottom-20 right-10 w-96 h-96 bg-pink-400/40 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="absolute top-32 right-32 w-48 h-48 bg-cyan-300/30 rounded-full blur-2xl" aria-hidden="true"></div>

    {{-- Sparkle dots --}}
    <div class="absolute inset-0 opacity-20" aria-hidden="true"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($showBreadcrumb && !empty($breadcrumbs))
            <nav class="inline-flex items-center gap-2 text-sm bg-white/15 backdrop-blur-md ring-1 ring-white/30 text-white px-4 py-1.5 rounded-full mb-5" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-yellow-200 transition">Beranda</a>
                @foreach($breadcrumbs as $crumb)
                    <span class="text-white/50">›</span>
                    @if(!empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}" class="hover:text-yellow-200 transition">{{ $crumb['label'] }}</a>
                    @else
                        <span class="font-bold">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        <div class="flex items-start gap-6">
            @if($heroIcon)
                <div class="hidden sm:flex shrink-0 w-20 h-20 rounded-3xl bg-white/15 backdrop-blur-md ring-2 ring-white/30 items-center justify-center rotate-3 hover:rotate-0 transition shadow-2xl shadow-purple-900/30">
                    <x-dynamic-component :component="'heroicon-o-'.$heroIcon" class="w-11 h-11 text-white" />
                </div>
            @endif
            <div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white drop-shadow-2xl tracking-tight">
                    {{ $heroTitle }}
                    <span class="inline-block animate-bounce">✨</span>
                </h1>
                @if($heroSubtitle)
                    <p class="mt-4 text-white/95 text-base sm:text-lg lg:text-xl max-w-2xl font-medium leading-relaxed">{{ $heroSubtitle }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Wave SVG --}}
    <svg class="absolute bottom-0 left-0 w-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" preserveAspectRatio="none">
        <path fill="rgb(253 242 248)" d="M0,32L60,37.3C120,43,240,53,360,58.7C480,64,600,64,720,53.3C840,43,960,21,1080,16C1200,11,1320,21,1380,26.7L1440,32L1440,80L1380,80C1320,80,1200,80,1080,80C960,80,840,80,720,80C600,80,480,80,360,80C240,80,120,80,60,80L0,80Z"></path>
    </svg>
</section>
