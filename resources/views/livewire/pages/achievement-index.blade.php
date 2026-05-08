<div>
    <x-site.page-hero
        key="prestasi"
        title="Prestasi Murid"
        subtitle="Kami percaya setiap anak adalah bintang. Berikut catatan prestasi terbaik siswa-siswi kami."
        icon="trophy"
        :breadcrumbs="[['label' => 'Prestasi']]"
    />

    <x-site.page-frame>
        @if($achievements->isEmpty())
            <p class="text-center text-slate-500 py-10">Belum ada prestasi yang ditampilkan.</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($achievements as $a)
                    <div class="bg-slate-50 rounded-2xl ring-1 ring-slate-200 hover:ring-emerald-400 hover:shadow-lg transition flex flex-col overflow-hidden">
                        <div class="aspect-square bg-slate-100 overflow-hidden">
                            @if($a->image)
                                <img src="{{ asset('storage/'.$a->image) }}" alt="{{ $a->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full grid place-items-center text-slate-400">
                                    <x-heroicon-o-trophy class="w-14 h-14" />
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            @if($a->institution)
                                <p class="text-emerald-600 italic font-semibold text-sm">{{ $a->institution }}</p>
                            @endif
                            <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $a->title }}</h3>
                            @if($a->excerpt)
                                <p class="text-slate-600 text-sm mt-2 line-clamp-3">{{ $a->excerpt }}</p>
                            @endif
                            <a href="{{ route('prestasi.show', $a->slug) }}"
                               class="mt-4 inline-flex justify-center items-center border border-slate-300 hover:border-emerald-600 hover:text-emerald-700 text-slate-700 rounded-full px-4 py-2 text-sm font-medium transition self-start">
                                Lihat Lebih Lengkap
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-10">{{ $achievements->links() }}</div>
        @endif
    </x-site.page-frame>
</div>
