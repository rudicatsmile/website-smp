<div class="max-w-5xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-slate-900">Download</h1>

    <div class="mt-6">
        <select wire:model.live="category" class="px-4 py-2 rounded-lg border border-slate-300">
            <option value="">Semua Kategori</option>
            @foreach($categories as $c)
                <option value="{{ $c->slug }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>

    @if($downloads->isEmpty())
        <p class="mt-8 text-slate-500">Belum ada file unduhan.</p>
    @else
        <ul class="mt-6 divide-y bg-white rounded-xl shadow-sm">
            @foreach($downloads as $d)
                <li class="p-4 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-slate-900">{{ $d->title }}</div>
                        <div class="text-xs text-slate-500 mt-1">
                            @if($d->category){{ $d->category->name }} · @endif
                            {{ $d->download_count }}x diunduh
                        </div>
                    </div>
                    <button wire:click="track({{ $d->id }})" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm">Unduh</button>
                </li>
            @endforeach
        </ul>
        <div class="mt-6">{{ $downloads->links() }}</div>
    @endif
</div>
