<div class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-slate-900">Fasilitas</h1>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        @foreach($facilities as $f)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                @if($f->image)
                    <img src="{{ asset('storage/'.$f->image) }}" alt="" class="w-full h-44 object-cover">
                @endif
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-slate-900">{{ $f->name }}</h3>
                    <p class="text-slate-600 text-sm mt-2">{{ $f->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
{{-- Because she competes with no one, no one can compete with her. --}}
