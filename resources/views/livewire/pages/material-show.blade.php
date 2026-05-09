<div>
    <x-site.page-hero key="materi" :title="$material->title" :subtitle="$material->category?->name" icon="heroicon-o-book-open" />

    <x-site.page-frame>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    @if($material->cover_image)
                        <img src="{{ $material->cover_url }}" alt="{{ $material->title }}" class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                            <svg class="w-20 h-20 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                    <div class="p-6 md:p-8">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">{{ $material->category?->name }}</span>
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">{{ $material->type_label }}</span>
                            <span class="px-3 py-1 bg-emerald-600 text-white rounded-full text-xs font-semibold">{{ $material->grade_label }}</span>
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold">{{ $material->curriculum_label }}</span>
                            @if($material->phase)
                                <span class="px-3 py-1 bg-sky-50 text-sky-700 rounded-full text-xs font-semibold">Fase {{ $material->phase }}</span>
                            @endif
                            @if($material->semester)
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold">
                                    {{ $material->semester === 'tahunan' ? 'Tahunan' : 'Semester ' . $material->semester }}
                                </span>
                            @endif
                            @if($material->academic_year)
                                <span class="px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-semibold">TA {{ $material->academic_year }}</span>
                            @endif
                        </div>

                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3">{{ $material->title }}</h1>

                        @if($material->excerpt)
                            <p class="text-slate-600 text-lg mb-6">{{ $material->excerpt }}</p>
                        @endif

                        @if($material->description)
                            <div class="prose prose-emerald max-w-none">
                                {!! $material->description !!}
                            </div>
                        @endif

                        @if($material->tags && count($material->tags) > 0)
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                <h4 class="text-sm font-semibold text-slate-700 mb-3">Tag</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($material->tags as $tag)
                                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Related --}}
                @if($related->count() > 0)
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 mb-4">Materi Terkait</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($related as $item)
                                <a href="{{ route('materials.show', $item->slug) }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition border border-slate-100 p-4 group">
                                    <div class="flex items-center gap-2 mb-2 text-xs text-slate-500">
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded font-semibold">{{ $item->type_label }}</span>
                                        <span>{{ $item->grade_label }}</span>
                                    </div>
                                    <h4 class="font-semibold text-slate-800 group-hover:text-emerald-600 transition line-clamp-2">{{ $item->title }}</h4>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Download Card --}}
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-white/80">File Materi</div>
                            <div class="font-semibold truncate">{{ $material->file_name ?: 'Materi.pdf' }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-white/90 mb-4 space-y-1">
                        <div>Ukuran: <strong>{{ $material->file_size_human }}</strong></div>
                        @if($material->file_mime)
                            <div>Format: <strong>{{ $material->file_mime }}</strong></div>
                        @endif
                        <div>Diunduh: <strong>{{ number_format($material->download_count) }}x</strong></div>
                    </div>
                    <a href="{{ route('materials.download', $material->slug) }}" class="block text-center w-full py-3 bg-white text-emerald-600 rounded-xl font-bold hover:bg-white/90 transition">
                        Unduh Materi
                    </a>
                </div>

                {{-- Author Card --}}
                @if($material->author)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h4 class="text-sm font-semibold text-slate-500 mb-4">Penulis</h4>
                        <div class="flex items-center gap-4">
                            @if($material->author->photo)
                                <img src="{{ $material->author->photo_url }}" alt="{{ $material->author->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-emerald-100">
                            @else
                                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">{{ substr($material->author->name, 0, 1) }}</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-800 truncate">{{ $material->author->name }}</div>
                                @if($material->author->position)
                                    <div class="text-sm text-slate-500 truncate">{{ $material->author->position }}</div>
                                @endif
                                <a href="{{ route('staff.show', $material->author->slug) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">Lihat Profil &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Meta --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h4 class="text-sm font-semibold text-slate-500 mb-4">Informasi</h4>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Diterbitkan</dt>
                            <dd class="font-medium text-slate-800">{{ $material->published_at?->translatedFormat('d M Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Dilihat</dt>
                            <dd class="font-medium text-slate-800">{{ number_format($material->view_count) }}x</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Diunduh</dt>
                            <dd class="font-medium text-slate-800">{{ number_format($material->download_count) }}x</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </x-site.page-frame>
</div>
