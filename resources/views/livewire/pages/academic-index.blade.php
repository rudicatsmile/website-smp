<div>
    <x-site.page-hero
        key="akademik"
        title="Akademik"
        subtitle="Program dan kegiatan akademik untuk mengembangkan potensi siswa secara menyeluruh."
        icon="academic-cap"
        :breadcrumbs="[['label' => 'Akademik']]"
    />

    <x-site.page-frame>
        @if($academics->isEmpty())
            <p class="text-center text-slate-500 py-10">Belum ada program akademik yang ditampilkan.</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($academics as $a)
                    <a href="{{ route('akademik.show', $a->slug) }}"
                       class="group block bg-slate-50 rounded-xl overflow-hidden ring-1 ring-slate-200 hover:ring-emerald-400 hover:shadow-lg transition">
                        <div class="aspect-[16/10] bg-slate-100 overflow-hidden">
                            @if($a->image)
                                <img src="{{ asset('storage/'.$a->image) }}" alt="{{ $a->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full grid place-items-center text-slate-400">
                                    <x-heroicon-o-academic-cap class="w-14 h-14" />
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-lg text-slate-900 group-hover:text-emerald-700 transition">{{ $a->name }}</h3>
                            @if($a->head_name)
                                <div class="text-sm text-emerald-700 mt-1">{{ $a->head_name }}</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </x-site.page-frame>
</div>
