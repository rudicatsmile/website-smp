{{-- Milleneal skin: navbar --}}
<header x-data="{ open: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 10; window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
        :class="(scrolled || open) ? 'bg-white/90 backdrop-blur-xl shadow-lg shadow-purple-500/5' : '{{ $transparent ? 'bg-transparent' : 'bg-white/90 backdrop-blur-xl shadow-lg shadow-purple-500/5' }}'"
        class="fixed top-0 left-0 right-0 z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if($settings->logo)
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-500 to-purple-600 blur-md opacity-50 group-hover:opacity-75 transition rounded-full"></div>
                        <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" class="relative h-10 w-10 object-contain rounded-full bg-white ring-2 ring-white">
                    </div>
                @else
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-600 text-white grid place-items-center font-extrabold shadow-lg shadow-purple-500/30 group-hover:rotate-12 transition">A9</div>
                @endif
                <div class="leading-tight">
                    <div :class="(scrolled || open) ? 'text-slate-900' : '{{ $transparent ? 'text-white drop-shadow-lg' : 'text-slate-900' }}'" class="font-extrabold transition-colors">{{ $settings->school_name }}</div>
                    @if($settings->tagline)
                        <div :class="(scrolled || open) ? 'text-purple-600' : '{{ $transparent ? 'text-pink-200 drop-shadow' : 'text-purple-600' }}'" class="text-xs font-medium transition-colors">✨ {{ $settings->tagline }}</div>
                    @endif
                </div>
            </a>

            <nav :class="(scrolled || open) ? 'text-slate-700' : '{{ $transparent ? 'text-white drop-shadow' : 'text-slate-700' }}'" class="hidden lg:flex items-center gap-1 text-sm font-semibold transition-colors">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">Beranda</a>

                {{-- Tentang Kami --}}
                <div x-data="{ dd: false }" class="relative" @mouseenter="dd=true" @mouseleave="dd=false">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">
                        Tentang Kami
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="dd ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="dd" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl shadow-purple-500/10 border border-purple-100 py-1.5 z-50">
                        <a href="{{ route('profil') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">🏫</span> Profil Sekolah
                        </a>
                        <a href="{{ route('fasilitas.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">🏗️</span> Fasilitas
                        </a>
                        <a href="{{ route('staff.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">👨‍🏫</span> Guru & Staf
                        </a>
                    </div>
                </div>

                {{-- Akademik --}}
                <div x-data="{ dd: false }" class="relative" @mouseenter="dd=true" @mouseleave="dd=false">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">
                        Akademik
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="dd ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="dd" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl shadow-purple-500/10 border border-purple-100 py-1.5 z-50">
                        <a href="{{ route('akademik.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">📚</span> Program Akademik
                        </a>
                        <a href="{{ route('materials.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">📖</span> Materi Belajar
                        </a>
                        <a href="{{ route('jadwal.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">🗓️</span> Jadwal Pelajaran
                        </a>
                    </div>
                </div>

                {{-- Kegiatan --}}
                <div x-data="{ dd: false }" class="relative" @mouseenter="dd=true" @mouseleave="dd=false">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">
                        Kegiatan
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="dd ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="dd" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl shadow-purple-500/10 border border-purple-100 py-1.5 z-50">
                        <a href="{{ route('prestasi.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">🏆</span> Prestasi
                        </a>
                        <a href="{{ route('ekskul.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">⚽</span> Ekstrakurikuler
                        </a>
                        <a href="{{ route('alumni.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">🎓</span> Alumni
                        </a>
                        <a href="{{ route('galeri.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">🖼️</span> Galeri
                        </a>
                    </div>
                </div>

                {{-- Informasi --}}
                <div x-data="{ dd: false }" class="relative" @mouseenter="dd=true" @mouseleave="dd=false">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">
                        Informasi
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="dd ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="dd" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-44 bg-white rounded-2xl shadow-xl shadow-purple-500/10 border border-purple-100 py-1.5 z-50">
                        <a href="{{ route('berita.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">📰</span> Berita
                        </a>
                        <a href="{{ route('download.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">⬇️</span> Download
                        </a>
                        <a href="{{ route('kontak') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-purple-50 hover:text-purple-700 text-sm rounded-xl mx-1">
                            <span class="text-base">📞</span> Kontak
                        </a>
                    </div>
                </div>

                <a href="{{ route('spmb.index') }}" class="ml-2 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-600 hover:from-pink-600 hover:to-indigo-700 text-white px-5 py-2 rounded-full font-bold shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:scale-105 transition">🚀 SPMB</a>
            </nav>

            <button @click="open = !open"
                    :class="(scrolled || open) ? 'text-slate-700 hover:bg-purple-100' : '{{ $transparent ? 'text-white hover:bg-white/10' : 'text-slate-700 hover:bg-purple-100' }}'"
                    class="lg:hidden p-2 rounded-full transition" aria-label="Toggle menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="lg:hidden pb-4 space-y-1 text-sm font-medium border-t border-purple-100 pt-3 mt-1">
            <a href="{{ route('home') }}" class="block py-2 px-3 rounded-xl hover:bg-purple-50">Beranda</a>

            {{-- Tentang Kami --}}
            <div x-data="{ acc: false }">
                <button @click="acc=!acc" class="w-full flex items-center justify-between py-2 px-3 rounded-xl hover:bg-purple-50 text-left">
                    <span>Tentang Kami</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="acc ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="acc" x-transition class="pl-4 pb-1 space-y-0.5">
                    <a href="{{ route('profil') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">🏫 Profil Sekolah</a>
                    <a href="{{ route('fasilitas.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">🏗️ Fasilitas</a>
                    <a href="{{ route('staff.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">👨‍🏫 Guru & Staf</a>
                </div>
            </div>

            {{-- Akademik --}}
            <div x-data="{ acc: false }">
                <button @click="acc=!acc" class="w-full flex items-center justify-between py-2 px-3 rounded-xl hover:bg-purple-50 text-left">
                    <span>Akademik</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="acc ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="acc" x-transition class="pl-4 pb-1 space-y-0.5">
                    <a href="{{ route('akademik.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">📚 Program Akademik</a>
                    <a href="{{ route('materials.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">📖 Materi Belajar</a>
                    <a href="{{ route('jadwal.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">🗓️ Jadwal Pelajaran</a>
                </div>
            </div>

            {{-- Kegiatan --}}
            <div x-data="{ acc: false }">
                <button @click="acc=!acc" class="w-full flex items-center justify-between py-2 px-3 rounded-xl hover:bg-purple-50 text-left">
                    <span>Kegiatan</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="acc ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="acc" x-transition class="pl-4 pb-1 space-y-0.5">
                    <a href="{{ route('prestasi.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">🏆 Prestasi</a>
                    <a href="{{ route('ekskul.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">⚽ Ekstrakurikuler</a>
                    <a href="{{ route('alumni.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">🎓 Alumni</a>
                    <a href="{{ route('galeri.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">🖼️ Galeri</a>
                </div>
            </div>

            {{-- Informasi --}}
            <div x-data="{ acc: false }">
                <button @click="acc=!acc" class="w-full flex items-center justify-between py-2 px-3 rounded-xl hover:bg-purple-50 text-left">
                    <span>Informasi</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="acc ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="acc" x-transition class="pl-4 pb-1 space-y-0.5">
                    <a href="{{ route('berita.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">📰 Berita</a>
                    <a href="{{ route('download.index') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">⬇️ Download</a>
                    <a href="{{ route('kontak') }}" class="block py-2 px-3 rounded-xl text-slate-600 hover:bg-purple-50 hover:text-purple-700">📞 Kontak</a>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('spmb.index') }}" class="block py-2.5 px-3 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold text-center">🚀 SPMB</a>
            </div>
        </div>
    </div>
</header>
