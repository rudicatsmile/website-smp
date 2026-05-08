<div class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="text-center max-w-3xl mx-auto">
            <p class="text-emerald-600 font-semibold italic">Prestasi Murid</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2">Bintang di Setiap Langkah</h1>
            <p class="text-slate-600 mt-3">
                Kami percaya setiap anak adalah bintang. Berikut catatan prestasi terbaik
                siswa-siswi sebagai wujud potensi yang terus berkembang.
            </p>
        </div>

        @if($achievements->isEmpty())
            <p class="text-center text-slate-500 mt-12">Belum ada prestasi yang ditampilkan.</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                @foreach($achievements as $a)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden">
                        <div class="aspect-square bg-slate-100 overflow-hidden">
                            @if($a->image)
                                <img src="{{ asset('storage/'.$a->image) }}" alt="{{ $a->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full grid place-items-center text-slate-400 text-sm">Tidak ada gambar</div>
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
                               class="mt-4 inline-flex justify-center items-center border border-slate-300 hover:border-emerald-600 hover:text-emerald-700 text-slate-700 rounded-full px-4 py-2 text-sm font-medium transition">
                                Lihat Lebih Lengkap
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-10">{{ $achievements->links() }}</div>
        @endif
    </div>
</div>
