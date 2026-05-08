<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-slate-900">Akademik</h1>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @foreach($academics as $a)
            <a href="{{ route('akademik.show', $a->slug) }}" class="block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                @if($a->image)
                    <img src="{{ asset('storage/'.$a->image) }}" alt="" class="w-full h-44 object-cover">
                @endif
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-slate-900">{{ $a->name }}</h3>
                    @if($a->head_name)<div class="text-sm text-emerald-700 mt-1">{{ $a->head_name }}</div>@endif
                </div>
            </a>
        @endforeach
    </div>
</div>
