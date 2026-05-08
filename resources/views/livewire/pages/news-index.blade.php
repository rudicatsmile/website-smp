<div>
    <x-site.page-hero
        key="berita"
        title="Berita & Pengumuman"
        subtitle="Informasi, pengumuman, dan kegiatan terkini dari lingkungan sekolah kami."
        icon="newspaper"
        :breadcrumbs="[['label' => 'Berita']]"
    />

    <x-site.page-frame>
        <div class="flex flex-col sm:flex-row gap-3 mb-8">
            <div class="relative flex-1">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari berita..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <select wire:model.live="category" class="px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->slug }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div wire:loading.delay class="mb-4 text-sm text-slate-500">Memuat...</div>

        @if($news->isEmpty())
            <p class="text-center text-slate-500 py-10">Belum ada berita.</p>
        @else
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($news as $n)
                    <article class="group bg-slate-50 rounded-xl overflow-hidden ring-1 ring-slate-200 hover:ring-emerald-400 hover:shadow-lg transition flex flex-col">
                        <a href="{{ route('berita.show', $n->slug) }}" class="block aspect-[16/10] bg-slate-100 overflow-hidden">
                            @if($n->thumbnail)
                                <img src="{{ asset('storage/'.$n->thumbnail) }}" alt="{{ $n->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full grid place-items-center text-slate-400">
                                    <x-heroicon-o-newspaper class="w-14 h-14" />
                                </div>
                            @endif
                        </a>
                        <div class="p-5 flex-1 flex flex-col">
                            @if($n->category)
                                <div class="text-xs text-emerald-700 font-semibold uppercase tracking-wide">{{ $n->category->name }}</div>
                            @endif
                            <h3 class="font-semibold mt-1 line-clamp-2 text-slate-900">
                                <a href="{{ route('berita.show', $n->slug) }}" class="hover:text-emerald-700">{{ $n->title }}</a>
                            </h3>
                            <p class="text-sm text-slate-600 mt-2 line-clamp-3">{{ $n->excerpt }}</p>
                            <div class="text-xs text-slate-400 mt-auto pt-3 flex items-center gap-1">
                                <x-heroicon-o-calendar class="w-3.5 h-3.5" />
                                {{ $n->published_at?->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-10">{{ $news->links() }}</div>
        @endif
    </x-site.page-frame>
</div>
{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
