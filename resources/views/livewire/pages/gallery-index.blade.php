<div>
    <x-site.page-hero
        key="galeri"
        title="Galeri"
        subtitle="Dokumentasi kegiatan dan momen berharga di lingkungan sekolah kami."
        icon="photo"
        :breadcrumbs="[['label' => 'Galeri']]"
    />

    <x-site.page-frame>
        @if($galleries->isEmpty())
            <p class="text-center text-slate-500 py-10">Belum ada album.</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($galleries as $g)
                    <a href="{{ route('galeri.show', $g->slug) }}"
                       class="group block bg-slate-50 rounded-xl overflow-hidden ring-1 ring-slate-200 hover:ring-emerald-400 hover:shadow-lg transition">
                        <div class="aspect-[4/3] bg-slate-100 overflow-hidden relative">
                            @if($g->cover)
                                <img src="{{ asset('storage/'.$g->cover) }}" alt="{{ $g->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full grid place-items-center text-slate-400">
                                    <x-heroicon-o-photo class="w-14 h-14" />
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 bg-black/60 backdrop-blur text-white text-xs px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                                <x-heroicon-s-photo class="w-3.5 h-3.5" />
                                {{ $g->items_count }}
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900 group-hover:text-emerald-700 transition">{{ $g->title }}</h3>
                            <div class="text-sm text-slate-500 mt-1">{{ $g->published_at?->translatedFormat('d M Y') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-10">{{ $galleries->links() }}</div>
        @endif
    </x-site.page-frame>
</div>
{{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
