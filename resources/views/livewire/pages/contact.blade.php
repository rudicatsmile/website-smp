<div class="max-w-6xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-10">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Kontak Kami</h1>
        <ul class="mt-6 space-y-3 text-slate-700">
            @if($settings->address)<li><strong>Alamat:</strong> {{ $settings->address }}</li>@endif
            @if($settings->phone)<li><strong>Telp:</strong> {{ $settings->phone }}</li>@endif
            @if($settings->whatsapp)<li><strong>WhatsApp:</strong> {{ $settings->whatsapp }}</li>@endif
            @if($settings->email)<li><strong>Email:</strong> {{ $settings->email }}</li>@endif
        </ul>
        @if($settings->maps_embed)
            <div class="mt-6 aspect-video">{!! $settings->maps_embed !!}</div>
        @endif
    </div>
    <div>
        <h2 class="text-2xl font-semibold text-slate-900">Kirim Pesan</h2>
        @if($sent)
            <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
                Pesan Anda berhasil terkirim. Kami akan segera membalas.
            </div>
        @endif
        <form wire:submit="submit" class="mt-4 space-y-3">
            <div>
                <label class="text-sm font-medium">Nama</label>
                <input type="text" wire:model="name" class="mt-1 w-full px-4 py-2 rounded-lg border border-slate-300">
                @error('name')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" wire:model="email" class="mt-1 w-full px-4 py-2 rounded-lg border border-slate-300">
                    @error('email')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Telepon</label>
                    <input type="text" wire:model="phone" class="mt-1 w-full px-4 py-2 rounded-lg border border-slate-300">
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Subjek</label>
                <input type="text" wire:model="subject" class="mt-1 w-full px-4 py-2 rounded-lg border border-slate-300">
                @error('subject')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm font-medium">Pesan</label>
                <textarea wire:model="message" rows="5" class="mt-1 w-full px-4 py-2 rounded-lg border border-slate-300"></textarea>
                @error('message')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-semibold">
                <span wire:loading.remove wire:target="submit">Kirim Pesan</span>
                <span wire:loading wire:target="submit">Mengirim...</span>
            </button>
        </form>
    </div>
</div>
{{-- Nothing in the world is as soft and yielding as water. --}}
