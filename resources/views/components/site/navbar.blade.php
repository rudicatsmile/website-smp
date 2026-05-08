@props(['settings'])
<header x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @if($settings->logo)
                    <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" class="h-10 w-10 object-contain">
                @else
                    <div class="h-10 w-10 rounded-full bg-emerald-600 text-white grid place-items-center font-bold">A9</div>
                @endif
                <div class="leading-tight">
                    <div class="font-semibold text-slate-900">{{ $settings->school_name }}</div>
                    @if($settings->tagline)
                        <div class="text-xs text-slate-500">{{ $settings->tagline }}</div>
                    @endif
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-emerald-600">Beranda</a>
                <a href="{{ route('profil') }}" class="hover:text-emerald-600">Profil</a>
                <a href="{{ route('akademik.index') }}" class="hover:text-emerald-600">Akademik</a>
                <a href="{{ route('fasilitas.index') }}" class="hover:text-emerald-600">Fasilitas</a>
                <a href="{{ route('prestasi.index') }}" class="hover:text-emerald-600">Prestasi</a>
                <a href="{{ route('galeri.index') }}" class="hover:text-emerald-600">Galeri</a>
                <a href="{{ route('berita.index') }}" class="hover:text-emerald-600">Berita</a>
                <a href="{{ route('download.index') }}" class="hover:text-emerald-600">Download</a>
                <a href="{{ route('kontak') }}" class="hover:text-emerald-600">Kontak</a>
                <a href="{{ route('spmb.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">SPMB</a>
            </nav>

            <button @click="open = !open" class="lg:hidden p-2 rounded text-slate-700 hover:bg-slate-100" aria-label="Toggle menu">
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
