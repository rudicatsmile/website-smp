<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-slate-900">Galeri</h1>
    @if($galleries->isEmpty())
        <p class="mt-8 text-slate-500">Belum ada album.</p>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            @foreach($galleries as $g)
                <a href="{{ route('galeri.show', $g->slug) }}" class="block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    @if($g->cover)
                        <img src="{{ asset('storage/'.$g->cover) }}" alt="" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-slate-200"></div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-900">{{ $g->title }}</h3>
                        <div class="text-sm text-slate-500 mt-1">{{ $g->items_count }} foto · {{ $g->published_at?->translatedFormat('d M Y') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $galleries->links() }}</div>
    @endif
</div>
{{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
