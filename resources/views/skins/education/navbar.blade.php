@props(['settings', 'transparent' => false])
<header x-data="{ open: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 10; window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
        :class="(scrolled || open) ? 'bg-white shadow-sm' : '{{ $transparent ? 'bg-transparent' : 'bg-white shadow-sm' }}'"
        class="fixed top-0 left-0 right-0 z-40 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @if($settings->logo)
                    <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" class="h-10 w-10 object-contain">
                @else
                    <div class="h-10 w-10 rounded-full bg-emerald-600 text-white grid place-items-center font-bold">A9</div>
                @endif
                <div class="leading-tight">
                    <div :class="(scrolled || open) ? 'text-slate-900' : '{{ $transparent ? 'text-white drop-shadow' : 'text-slate-900' }}'" class="font-semibold transition-colors">{{ $settings->school_name }}</div>
                    @if($settings->tagline)
                        <div :class="(scrolled || open) ? 'text-slate-500' : '{{ $transparent ? 'text-slate-100 drop-shadow' : 'text-slate-500' }}'" class="text-xs transition-colors">{{ $settings->tagline }}</div>
                    @endif
                </div>
            </a>

            <nav :class="(scrolled || open) ? 'text-slate-700' : '{{ $transparent ? 'text-white drop-shadow' : 'text-slate-700' }}'" class="hidden lg:flex items-center gap-6 text-sm font-medium transition-colors">
                <a href="{{ route('home') }}" class="hover:text-emerald-400">Beranda</a>
                <a href="{{ route('profil') }}" class="hover:text-emerald-400">Profil</a>
                <a href="{{ route('akademik.index') }}" class="hover:text-emerald-400">Akademik</a>
                <a href="{{ route('fasilitas.index') }}" class="hover:text-emerald-400">Fasilitas</a>
                <a href="{{ route('prestasi.index') }}" class="hover:text-emerald-400">Prestasi</a>
                <a href="{{ route('galeri.index') }}" class="hover:text-emerald-400">Galeri</a>
                <a href="{{ route('berita.index') }}" class="hover:text-emerald-400">Berita</a>
                <a href="{{ route('download.index') }}" class="hover:text-emerald-400">Download</a>
                <a href="{{ route('kontak') }}" class="hover:text-emerald-400">Kontak</a>
                <a href="{{ route('spmb.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">SPMB</a>
            </nav>

            <button @click="open = !open"
                    :class="(scrolled || open) ? 'text-slate-700 hover:bg-slate-100' : '{{ $transparent ? 'text-white hover:bg-white/10' : 'text-slate-700 hover:bg-slate-100' }}'"
                    class="lg:hidden p-2 rounded transition-colors" aria-label="Toggle menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <div x-show="open" x-cloak class="lg:hidden pb-4 space-y-2 text-sm">
            <a href="{{ route('home') }}" class="block py-2">Beranda</a>
            <a href="{{ route('profil') }}" class="block py-2">Profil</a>
            <a href="{{ route('akademik.index') }}" class="block py-2">Akademik</a>
            <a href="{{ route('fasilitas.index') }}" class="block py-2">Fasilitas</a>
            <a href="{{ route('prestasi.index') }}" class="block py-2">Prestasi</a>
            <a href="{{ route('galeri.index') }}" class="block py-2">Galeri</a>
            <a href="{{ route('berita.index') }}" class="block py-2">Berita</a>
            <a href="{{ route('download.index') }}" class="block py-2">Download</a>
            <a href="{{ route('kontak') }}" class="block py-2">Kontak</a>
            <a href="{{ route('spmb.index') }}" class="block py-2 text-emerald-700 font-semibold">SPMB</a>
        </div>
    </div>
</header>
