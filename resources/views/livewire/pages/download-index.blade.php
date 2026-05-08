<div>
    <x-site.page-hero
        key="download"
        title="Download"
        subtitle="Unduh berkas, formulir, dan dokumen resmi sekolah."
        icon="arrow-down-tray"
        :breadcrumbs="[['label' => 'Download']]"
    />

    <x-site.page-frame>
        <div class="mb-6">
            <select wire:model.live="category" class="px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->slug }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        @if($downloads->isEmpty())
            <p class="text-center text-slate-500 py-10">Belum ada file unduhan.</p>
        @else
            <ul class="divide-y divide-slate-200 bg-slate-50 rounded-xl ring-1 ring-slate-200 overflow-hidden">
                @foreach($downloads as $d)
                    <li class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-white transition">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="shrink-0 w-12 h-12 rounded-lg bg-emerald-100 text-emerald-700 grid place-items-center">
                                <x-heroicon-o-document class="w-6 h-6" />
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-slate-900 truncate">{{ $d->title }}</div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center gap-2 flex-wrap">
                                    @if($d->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-medium">{{ $d->category->name }}</span>
                                    @endif
                                    <span class="inline-flex items-center gap-1">
                                        <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" />
                                        {{ $d->download_count }}x
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button wire:click="track({{ $d->id }})"
                                class="shrink-0 inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                            Unduh
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="mt-8">{{ $downloads->links() }}</div>
        @endif
    </x-site.page-frame>
</div>
