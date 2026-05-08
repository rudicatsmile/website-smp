<div class="bg-slate-50">
    <div class="max-w-5xl mx-auto px-6 py-12">
        <a href="{{ route('prestasi.index') }}" class="text-emerald-700 hover:underline text-sm">&larr; Kembali ke daftar prestasi</a>

        <article class="bg-white rounded-2xl shadow-sm overflow-hidden mt-4">
            @if($achievement->image)
                <div class="bg-slate-100 flex items-center justify-center">
                    <img src="{{ asset('storage/'.$achievement->image) }}" alt="{{ $achievement->title }}" class="w-full max-h-[60vh] object-contain">
                </div>
            @endif

            <div class="p-6 sm:p-10">
                @if($achievement->institution)
                    <p class="text-emerald-600 italic font-semibold">{{ $achievement->institution }}</p>
                @endif
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">{{ $achievement->title }}</h1>

                <div class="mt-4 flex flex-wrap gap-2 text-sm">
                    @if($achievement->level)
                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full font-medium">Tingkat: {{ $achievement->level }}</span>
                    @endif
                    @if($achievement->rank)
                        <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-full font-medium">{{ $achievement->rank }}</span>
                    @endif
                    @if($achievement->achieved_at)
                        <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full">{{ $achievement->achieved_at->translatedFormat('d F Y') }}</span>
                    @endif
                </div>

                @if($achievement->excerpt)
                    <p class="text-slate-700 mt-6 text-lg">{{ $achievement->excerpt }}</p>
                @endif

                @if($achievement->description)
                    <div class="prose prose-slate max-w-none mt-6">{!! $achievement->description !!}</div>
                @endif
            </div>
        </article>

        @if($related->isNotEmpty())
            <section class="mt-12">
                <h2 class="text-xl font-bold text-slate-900">Prestasi Lainnya</h2>
                <div class="grid sm:grid-cols-3 gap-6 mt-4">
                    @foreach($related as $r)
                        <a href="{{ route('prestasi.show', $r->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                            @if($r->image)
                                <img src="{{ asset('storage/'.$r->image) }}" alt="" class="w-full h-40 object-cover">
                            @endif
                            <div class="p-4">
                                @if($r->institution)
                                    <p class="text-emerald-600 italic text-xs font-semibold">{{ $r->institution }}</p>
                                @endif
                                <h3 class="font-semibold text-slate-900 mt-1">{{ $r->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
