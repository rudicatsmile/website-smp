<div>
    <x-site.page-hero
        key="ekskul"
        title="Ekstrakurikuler"
        subtitle="Temukan kegiatan yang sesuai minat dan bakat. Daftarkan dirimu dan kembangkan potensimu bersama kami."
        icon="user-group"
        :breadcrumbs="[['label' => 'Ekstrakurikuler']]"
    />

    <x-site.page-frame>
        {{-- Filter kategori --}}
        <div class="mb-6 flex flex-wrap gap-2">
            @foreach(['' => 'Semua', 'olahraga' => 'Olahraga', 'seni' => 'Seni & Budaya', 'keagamaan' => 'Keagamaan', 'akademik' => 'Akademik', 'lainnya' => 'Lainnya'] as $val => $label)
                <button wire:click="$set('category', '{{ $val }}')"
                    class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
                        {{ $category === $val
                            ? 'bg-emerald-600 border-emerald-600 text-white'
                            : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-400 hover:text-emerald-700' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Grid --}}
        @if($ekskuls->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <x-heroicon-o-user-group class="mx-auto mb-3 w-12 h-12 opacity-40" />
                <p>Tidak ada ekskul ditemukan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ekskuls as $ekskul)
                    <a href="{{ route('ekskul.show', $ekskul) }}" wire:navigate
                       class="group bg-slate-50 rounded-2xl ring-1 ring-slate-200 hover:ring-emerald-400 hover:shadow-lg transition overflow-hidden flex flex-col">
                        {{-- Cover --}}
                        <div class="aspect-video overflow-hidden bg-slate-100">
                            @if($ekskul->cover)
                                <img src="{{ $ekskul->cover_url }}" alt="{{ $ekskul->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full grid place-items-center text-slate-300">
                                    <x-heroicon-o-user-group class="w-14 h-14" />
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="font-bold text-slate-900 group-hover:text-emerald-700 transition text-base leading-tight">
                                    {{ $ekskul->name }}
                                </h3>
                                <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-semibold
                                    {{ match($ekskul->category) {
                                        'olahraga'  => 'bg-green-100 text-green-700',
                                        'seni'      => 'bg-yellow-100 text-yellow-700',
                                        'keagamaan' => 'bg-blue-100 text-blue-700',
                                        'akademik'  => 'bg-purple-100 text-purple-700',
                                        default     => 'bg-slate-100 text-slate-600',
                                    } }}">
                                    {{ match($ekskul->category) {
                                        'olahraga'  => 'Olahraga',
                                        'seni'      => 'Seni',
                                        'keagamaan' => 'Keagamaan',
                                        'akademik'  => 'Akademik',
                                        default     => 'Lainnya',
                                    } }}
                                </span>
                            </div>

                            @if($ekskul->coach)
                                <p class="text-xs text-emerald-600 italic font-semibold">Pembina: {{ $ekskul->coach->name }}</p>
                            @endif

                            <div class="mt-auto flex items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-200 mt-3">
                                <span>{{ $ekskul->members_count }} anggota</span>
                                @if($ekskul->quota)
                                    <span>Kuota: {{ $ekskul->quota }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </x-site.page-frame>
</div>
