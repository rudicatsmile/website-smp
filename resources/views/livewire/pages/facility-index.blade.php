<div>
    <x-site.page-hero
        key="fasilitas"
        title="Fasilitas"
        subtitle="Sarana dan prasarana yang mendukung kegiatan belajar mengajar yang berkualitas."
        icon="building-library"
        :breadcrumbs="[['label' => 'Fasilitas']]"
    />

    <x-site.page-frame>
        @if($facilities->isEmpty())
            <p class="text-center text-slate-500 py-10">Belum ada fasilitas yang ditampilkan.</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $f)
                    <div class="group bg-slate-50 rounded-xl overflow-hidden ring-1 ring-slate-200 hover:ring-emerald-400 hover:shadow-lg transition">
                        <div class="aspect-[16/10] bg-slate-100 overflow-hidden">
                            @if($f->image)
                                <img src="{{ asset('storage/'.$f->image) }}" alt="{{ $f->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full grid place-items-center text-slate-400">
                                    <x-heroicon-o-building-library class="w-14 h-14" />
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-lg text-slate-900">{{ $f->name }}</h3>
                            @if($f->description)
                                <p class="text-slate-600 text-sm mt-2 line-clamp-3">{{ $f->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-site.page-frame>
</div>
{{-- Because she competes with no one, no one can compete with her. --}}
