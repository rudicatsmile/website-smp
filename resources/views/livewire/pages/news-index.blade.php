<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-slate-900">Berita & Pengumuman</h1>

    <div class="flex flex-col sm:flex-row gap-3 mt-6">
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Cari berita..."
               class="flex-1 px-4 py-2 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
        <select wire:model.live="category" class="px-4 py-2 rounded-lg border border-slate-300">
            <option value="">Semua Kategori</option>
            @foreach($categories as $c)
                <option value="{{ $c->slug }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading.delay class="mt-4 text-sm text-slate-500">Memuat...</div>

    @if($news->isEmpty())
        <p class="mt-10 text-slate-500">Belum ada berita.</p>
    @else
        <div class="grid md:grid-cols-3 gap-6 mt-8">
            @foreach($news as $n)
                <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    @if($n->thumbnail)
                        <img src="{{ asset('storage/'.$n->thumbnail) }}" alt="" class="w-full h-44 object-cover">
                    @endif
                    <div class="p-5">
                        @if($n->category)<div class="text-xs text-emerald-700 font-semibold uppercase">{{ $n->category->name }}</div>@endif
                        <h3 class="font-semibold mt-1 line-clamp-2"><a href="{{ route('berita.show', $n->slug) }}" class="hover:text-emerald-700">{{ $n->title }}</a></h3>
                        <p class="text-sm text-slate-600 mt-2 line-clamp-3">{{ $n->excerpt }}</p>
                        <div class="text-xs text-slate-400 mt-3">{{ $n->published_at?->translatedFormat('d M Y') }}</div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $news->links() }}</div>
    @endif
</div>
{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
