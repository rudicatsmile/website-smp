<div class="max-w-4xl mx-auto px-6 py-12">
    <a href="{{ route('berita.index') }}" class="text-emerald-700 hover:underline text-sm">&larr; Kembali ke Berita</a>
    @if($news->category)
        <div class="text-xs text-emerald-700 font-semibold uppercase mt-4">{{ $news->category->name }}</div>
    @endif
    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2">{{ $news->title }}</h1>
    <div class="text-sm text-slate-500 mt-2">
        {{ $news->published_at?->translatedFormat('d F Y') }}
        @if($news->author) · oleh {{ $news->author->name }} @endif
        · {{ $news->views }}x dilihat
    </div>
    @if($news->thumbnail)
        <img src="{{ asset('storage/'.$news->thumbnail) }}" alt="" class="w-full rounded-xl mt-6">
    @endif
    <article class="prose max-w-none mt-6">{!! $news->body !!}</article>

    @if($news->tags->isNotEmpty())
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach($news->tags as $tag)
                <span class="px-3 py-1 bg-slate-200 text-slate-700 rounded-full text-xs">#{{ $tag->name }}</span>
            @endforeach
        </div>
    @endif

    @if($related->isNotEmpty())
        <section class="mt-12">
            <h2 class="text-xl font-semibold text-slate-900">Berita Terkait</h2>
            <div class="grid sm:grid-cols-3 gap-4 mt-4">
                @foreach($related as $r)
                    <a href="{{ route('berita.show', $r->slug) }}" class="block bg-white rounded-lg p-4 shadow-sm hover:shadow-md">
                        <div class="text-sm font-semibold line-clamp-2">{{ $r->title }}</div>
                        <div class="text-xs text-slate-400 mt-2">{{ $r->published_at?->translatedFormat('d M Y') }}</div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
