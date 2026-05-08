<div>
    {{-- Hero Slider (full bleed, behind transparent navbar) --}}
    @if($sliders->isNotEmpty())
        <section x-data="{ active: 0, slides: {{ $sliders->count() }} }"
                 x-init="setInterval(() => active = (active + 1) % slides, 5000)"
                 class="relative h-[520px] sm:h-[620px] overflow-hidden bg-slate-900">
            @foreach($sliders as $i => $slide)
                <div x-show="active === {{ $i }}" x-transition.opacity.duration.700ms class="absolute inset-0">
                    @if($slide->image)
                        <img src="{{ asset('storage/'.$slide->image) }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/60"></div>
                    <div class="absolute inset-0 flex items-center pt-16">
                        <div class="max-w-7xl mx-auto px-6 text-white w-full">
                            <h1 class="text-3xl sm:text-5xl font-bold drop-shadow-lg">{{ $slide->title }}</h1>
                            @if($slide->subtitle)<p class="mt-3 text-lg sm:text-xl text-slate-100 max-w-2xl drop-shadow">{{ $slide->subtitle }}</p>@endif
                            @if($slide->link_url)
                                <a href="{{ $slide->link_url }}" class="inline-block mt-6 bg-emerald-600 hover:bg-emerald-700 px-6 py-3 rounded-lg font-semibold">
                                    {{ $slide->link_text ?? 'Selengkapnya' }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Slider indicators --}}
            @if($sliders->count() > 1)
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    @foreach($sliders as $i => $slide)
                        <button @click="active = {{ $i }}"
                                :class="active === {{ $i }} ? 'bg-white w-8' : 'bg-white/50 w-2'"
                                class="h-2 rounded-full transition-all"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- Fasilitas --}}
    @if($facilities->isNotEmpty())
        <section class="max-w-7xl mx-auto px-6 py-16">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Fasilitas Unggulan</h2>
            <p class="text-slate-600 mt-2">Mendukung kegiatan belajar mengajar yang berkualitas.</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @foreach($facilities as $f)
                    <a href="{{ route('fasilitas.index') }}" class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                        @if($f->image)
                            <img src="{{ asset('storage/'.$f->image) }}" alt="{{ $f->name }}" class="w-full h-44 object-cover group-hover:scale-105 transition">
                        @else
                            <div class="w-full h-44 bg-slate-100 grid place-items-center text-slate-400 text-sm">Tidak ada gambar</div>
                        @endif
                        <div class="p-5">
                            <h3 class="font-semibold text-lg text-slate-900">{{ $f->name }}</h3>
                            @if($f->description)
                                <p class="text-slate-600 text-sm mt-2 line-clamp-2">{{ $f->description }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Program Unggulan --}}
    @if($programs->isNotEmpty())
        <section class="bg-gradient-to-br from-emerald-50 to-teal-50">
            <div class="max-w-7xl mx-auto px-6 py-16">
                <div class="text-center max-w-3xl mx-auto">
                    <p class="text-emerald-600 font-semibold italic">Program Unggulan</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2">Kembangkan Potensi Anak</h2>
                    <p class="text-slate-600 mt-3">
                        Program-program unggulan kami dirancang untuk mengembangkan potensi dan bakat siswa
                        secara optimal.
                    </p>
                    <a href="{{ route('program.index') }}"
                       class="inline-flex justify-center items-center mt-5 border border-slate-300 hover:border-emerald-600 hover:text-emerald-700 text-slate-700 rounded-full px-6 py-2.5 text-sm font-medium transition">
                        Lihat Semua Program
                    </a>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                    @foreach($programs as $program)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                            @if($program->image)
                                <div class="h-48 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                    <img src="{{ asset('storage/'.$program->image) }}"
                                         alt="{{ $program->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                    <x-heroicon-o-sparkles class="w-16 h-16 text-white opacity-80" />
                                </div>
                            @endif

                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-slate-900">{{ $program->title }}</h3>
                                @if($program->excerpt)
                                    <p class="text-slate-600 text-sm mt-2 line-clamp-2">{{ $program->excerpt }}</p>
                                @endif

                                <div class="mt-auto pt-4">
                                    @if($program->button_link)
                                        <a href="{{ $program->button_link }}"
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                                            {{ $program->button_text }}
                                            <x-heroicon-o-arrow-right class="w-4 h-4 ml-2" />
                                        </a>
                                    @else
                                        <a href="{{ route('program.show', $program->slug) }}"
                                           class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                                            {{ $program->button_text }}
                                            <x-heroicon-o-arrow-right class="w-4 h-4 ml-2" />
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Prestasi Murid --}}
    @if($achievements->isNotEmpty())
        <section class="bg-slate-50">
            <div class="max-w-7xl mx-auto px-6 py-16">
                <div class="text-center max-w-3xl mx-auto">
                    <p class="text-emerald-600 font-semibold italic">Prestasi Murid</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2">Bintang di Setiap Langkah</h2>
                    <p class="text-slate-600 mt-3">
                        Kami percaya setiap anak adalah bintang. Di sini kami hadirkan prestasi terbaik
                        siswa sebagai wujud potensi yang terus berkembang.
                    </p>
                    <a href="{{ route('prestasi.index') }}"
                       class="inline-flex justify-center items-center mt-5 border border-slate-300 hover:border-emerald-600 hover:text-emerald-700 text-slate-700 rounded-full px-6 py-2.5 text-sm font-medium transition">
                        Lihat Semua Prestasi
                    </a>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                    @foreach($achievements as $a)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden">
                            <div class="aspect-square bg-slate-100 overflow-hidden">
                                @if($a->image)
                                    <img src="{{ asset('storage/'.$a->image) }}" alt="{{ $a->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full grid place-items-center text-slate-400 text-sm">Tidak ada gambar</div>
                                @endif
                            </div>
                            <div class="p-5 flex-1 flex flex-col">
                                @if($a->institution)
                                    <p class="text-emerald-600 italic font-semibold text-sm">{{ $a->institution }}</p>
                                @endif
                                <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $a->title }}</h3>
                                @if($a->excerpt)
                                    <p class="text-slate-600 text-sm mt-2 line-clamp-3">{{ $a->excerpt }}</p>
                                @endif
                                <a href="{{ route('prestasi.show', $a->slug) }}"
                                   class="mt-auto pt-4 inline-flex justify-center items-center border border-slate-300 hover:border-emerald-600 hover:text-emerald-700 text-slate-700 rounded-full px-4 py-2 text-sm font-medium transition self-start">
                                    Lihat Lebih Lengkap
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Berita Terbaru --}}
    @if($latestNews->isNotEmpty())
        <section class="bg-white">
            <div class="max-w-7xl mx-auto px-6 py-16">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Berita Terbaru</h2>
                        <p class="text-slate-600 mt-2">Informasi & kegiatan terkini.</p>
                    </div>
                    <a href="{{ route('berita.index') }}" class="text-emerald-700 hover:underline font-medium">Lihat Semua</a>
                </div>
                <div class="grid md:grid-cols-3 gap-6 mt-8">
                    @foreach($latestNews as $n)
                        <article class="bg-slate-50 rounded-xl overflow-hidden hover:shadow-md transition">
                            @if($n->thumbnail)
                                <img src="{{ asset('storage/'.$n->thumbnail) }}" alt="" class="w-full h-44 object-cover">
                            @endif
                            <div class="p-5">
                                @if($n->category)<div class="text-xs text-emerald-700 font-semibold uppercase">{{ $n->category->name }}</div>@endif
                                <h3 class="font-semibold text-slate-900 mt-1 line-clamp-2"><a href="{{ route('berita.show', $n->slug) }}" class="hover:text-emerald-700">{{ $n->title }}</a></h3>
                                <p class="text-sm text-slate-600 mt-2 line-clamp-3">{{ $n->excerpt }}</p>
                                <div class="text-xs text-slate-400 mt-3">{{ $n->published_at?->translatedFormat('d M Y') }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
