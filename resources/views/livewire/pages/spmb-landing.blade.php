<div class="max-w-5xl mx-auto px-6 py-12">
    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900">SPMB - Penerimaan Murid Baru</h1>

    @if($period)
        <div class="mt-8 bg-white rounded-xl p-8 shadow-sm border border-emerald-100">
            <h2 class="text-2xl font-bold text-emerald-700">{{ $period->name }}</h2>
            <div class="grid sm:grid-cols-3 gap-4 mt-4 text-sm">
                <div><div class="text-slate-500">Periode</div><div class="font-medium">{{ $period->start_date->translatedFormat('d M Y') }} - {{ $period->end_date->translatedFormat('d M Y') }}</div></div>
                <div><div class="text-slate-500">Kuota</div><div class="font-medium">{{ $period->quota }} siswa</div></div>
                <div><div class="text-slate-500">Biaya Pendaftaran</div><div class="font-medium">Rp {{ number_format($period->fee, 0, ',', '.') }}</div></div>
            </div>
            @if($period->description)
                <p class="text-slate-700 mt-6">{{ $period->description }}</p>
            @endif
            <div class="flex flex-wrap gap-3 mt-8">
                <a href="{{ route('spmb.register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-bold">Daftar Sekarang</a>
                <a href="{{ route('spmb.status') }}" class="bg-white border border-emerald-600 text-emerald-700 hover:bg-emerald-50 px-6 py-3 rounded-lg font-semibold">Cek Status Pendaftaran</a>
            </div>
        </div>
    @else
        <div class="mt-8 p-6 bg-amber-50 border border-amber-200 rounded-xl text-amber-800">
            Pendaftaran SPMB belum dibuka. Silakan kembali lagi nanti atau hubungi kami untuk informasi lebih lanjut.
        </div>
    @endif

    <section class="mt-12">
        <h2 class="text-xl font-semibold text-slate-900">Persyaratan Pendaftaran</h2>
        <ul class="list-disc pl-6 mt-3 text-slate-700 space-y-1">
            <li>Fotokopi Kartu Keluarga (KK)</li>
            <li>Fotokopi Akta Kelahiran</li>
            <li>Pas foto terbaru</li>
            <li>Fotokopi Ijazah / SKL</li>
            <li>Fotokopi Rapor SD/MI semester 1-6</li>
        </ul>
    </section>
</div>
{{-- Because she competes with no one, no one can compete with her. --}}
