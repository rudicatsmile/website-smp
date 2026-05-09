<div>
    <x-site.page-hero key="materi" title="Bank Materi & Modul Ajar" subtitle="Modul ajar, RPP, LKPD, dan bahan ajar Kurikulum Merdeka." icon="heroicon-o-book-open" />

    <x-site.page-frame>
        <div class="space-y-8">
            {{-- Search & Filter --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-4">
                        <input
                            type="text"
                            wire:model.live.debounce.400ms="search"
                            placeholder="Cari judul, ringkasan, atau tag..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
                        >
                    </div>
                    <div class="md:col-span-2">
                        <select wire:model.live="categoryId" class="w-full px-3 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Semua Mapel</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <select wire:model.live="type" class="w-full px-3 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Semua Tipe</option>
                            <option value="modul_ajar">Modul Ajar</option>
                            <option value="rpp">RPP</option>
                            <option value="lkpd">LKPD</option>
                            <option value="bahan_ajar">Bahan Ajar</option>
                            <option value="atp">ATP</option>
                            <option value="cp">CP</option>
                            <option value="silabus">Silabus</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <select wire:model.live="grade" class="w-full px-3 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Semua Kelas</option>
                            <option value="7">Kelas 7</option>
                            <option value="8">Kelas 8</option>
                            <option value="9">Kelas 9</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <select wire:model.live="curriculum" class="w-full px-3 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Semua Kurikulum</option>
                            <option value="merdeka">Merdeka</option>
                            <option value="k13">K-13</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                @if($search || $categoryId || $type || $grade || $curriculum)
                    <div class="mt-4">
                        <button wire:click="clearFilters" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                            &times; Bersihkan filter
                        </button>
                    </div>
                @endif
            </div>

            {{-- Featured --}}
            @if($featured->count() > 0)
                <div>
                    <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118L10 13.347l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L3.567 7.819c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Materi Unggulan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($featured as $item)
                            <a href="{{ route('materials.show', $item->slug) }}" class="group block bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-2 py-1 bg-white/20 rounded-md text-xs font-medium">{{ $item->category?->name }}</span>
                                    <span class="px-2 py-1 bg-white/20 rounded-md text-xs font-medium">{{ $item->type_label }}</span>
                                </div>
                                <h3 class="text-lg font-bold mb-2 line-clamp-2 group-hover:underline">{{ $item->title }}</h3>
                                <p class="text-white/90 text-sm line-clamp-2">{{ $item->excerpt }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Grid --}}
            @if($materials->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($materials as $material)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-100 group flex flex-col">
                            <div class="relative h-40 bg-gradient-to-br from-emerald-50 to-teal-100 flex items-center justify-center overflow-hidden">
                                @if($material->cover_image)
                                    <img src="{{ $material->cover_url }}" alt="{{ $material->title }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-16 h-16 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @endif
                                <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                                    <span class="px-2 py-1 bg-white/90 backdrop-blur-sm rounded-md text-xs font-semibold text-emerald-700">{{ $material->category?->name }}</span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-xs font-semibold">{{ $material->grade_label }}</span>
                                </div>
                            </div>
                            <div class="p-5 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 mb-2 text-xs text-slate-500">
                                    <span class="px-2 py-0.5 bg-slate-100 rounded">{{ $material->type_label }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $material->curriculum_label }}</span>
                                </div>
                                <h3 class="text-base font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition line-clamp-2">{{ $material->title }}</h3>
                                @if($material->excerpt)
                                    <p class="text-sm text-slate-600 line-clamp-2 mb-3">{{ $material->excerpt }}</p>
                                @endif
                                @if($material->author)
                                    <div class="flex items-center gap-2 mb-4 text-xs text-slate-500">
                                        @if($material->author->photo)
                                            <img src="{{ $material->author->photo_url }}" class="w-6 h-6 rounded-full object-cover" alt="">
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">{{ substr($material->author->name, 0, 1) }}</div>
                                        @endif
                                        <span>{{ $material->author->name }}</span>
                                    </div>
                                @endif
                                <div class="mt-auto flex items-center justify-between">
                                    <a href="{{ route('materials.show', $material->slug) }}" class="inline-flex items-center text-emerald-600 font-medium hover:text-emerald-700 transition text-sm">
                                        Lihat Detail
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                    <span class="text-xs text-slate-400">{{ $material->download_count }} unduh</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($materials->hasPages())
                    <div class="flex justify-center">
                        {{ $materials->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-slate-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-700 mb-2">Belum ada materi</h3>
                    <p class="text-slate-500">Coba ubah kata kunci atau filter Anda.</p>
                </div>
            @endif
        </div>
    </x-site.page-frame>
</div>
