<div class="max-w-7xl mx-auto px-6 py-12" x-data="{ open: false, src: '' }">
    <a href="{{ route('galeri.index') }}" class="text-emerald-700 hover:underline text-sm">&larr; Kembali ke Galeri</a>
    <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ $gallery->title }}</h1>
    @if($gallery->description)<p class="text-slate-600 mt-2">{{ $gallery->description }}</p>@endif

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mt-8">
        @foreach($gallery->items as $item)
            <button type="button" @click="src='{{ asset('storage/'.$item->image) }}'; open=true" class="block group">
                <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->caption }}" class="w-full h-40 object-cover rounded-lg group-hover:opacity-90">
            </button>
        @endforeach
    </div>

    <div x-show="open" x-cloak @keydown.escape.window="open=false" @click="open=false" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
        <img :src="src" alt="" class="max-h-[90vh] max-w-[90vw] rounded">
    </div>
</div>
{{-- Because she competes with no one, no one can compete with her. --}}
