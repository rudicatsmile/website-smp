<div>
    <x-site.page-hero
        key="kontak"
        title="Kontak Kami"
        subtitle="Hubungi kami untuk pertanyaan, saran, atau informasi lebih lanjut."
        icon="chat-bubble-left-right"
        :breadcrumbs="[['label' => 'Kontak']]"
    />

    <x-site.page-frame>
        <div class="grid md:grid-cols-2 gap-8">
            {{-- Info & Map --}}
            <div>
                <h2 class="text-xl font-semibold text-emerald-700 flex items-center gap-2">
                    <span class="inline-block w-1.5 h-5 bg-emerald-600 rounded"></span>
                    Informasi Kontak
                </h2>
                <ul class="mt-5 space-y-4">
                    @if($settings->address)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 grid place-items-center">
                                <x-heroicon-o-map-pin class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Alamat</div>
                                <div class="text-slate-800">{{ $settings->address }}</div>
                            </div>
                        </li>
                    @endif
                    @if($settings->phone)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 grid place-items-center">
                                <x-heroicon-o-phone class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Telepon</div>
                                <a href="tel:{{ $settings->phone }}" class="text-slate-800 hover:text-emerald-700">{{ $settings->phone }}</a>
                            </div>
                        </li>
                    @endif
                    @if($settings->whatsapp)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 grid place-items-center">
                                <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="text-xs uppercase tracking-wide text-slate-500 font-semibold">WhatsApp</div>
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->whatsapp) }}"
                                   target="_blank"
                                   class="text-slate-800 hover:text-emerald-700">{{ $settings->whatsapp }}</a>
                            </div>
                        </li>
                    @endif
                    @if($settings->email)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 grid place-items-center">
                                <x-heroicon-o-envelope class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Email</div>
                                <a href="mailto:{{ $settings->email }}" class="text-slate-800 hover:text-emerald-700">{{ $settings->email }}</a>
                            </div>
                        </li>
                    @endif
                </ul>

                @if($settings->maps_embed)
                    <div class="mt-6 aspect-video rounded-xl overflow-hidden ring-1 ring-slate-200">
                        {!! $settings->maps_embed !!}
                    </div>
                @endif
            </div>

            {{-- Form --}}
            <div class="bg-slate-50 rounded-xl ring-1 ring-slate-200 p-6">
                <h2 class="text-xl font-semibold text-emerald-700 flex items-center gap-2">
                    <span class="inline-block w-1.5 h-5 bg-emerald-600 rounded"></span>
                    Kirim Pesan
                </h2>

                @if($sent)
                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-start gap-2">
                        <x-heroicon-o-check-circle class="w-5 h-5 shrink-0 mt-0.5" />
                        <div>Pesan Anda berhasil terkirim. Kami akan segera membalas.</div>
                    </div>
                @endif

                <form wire:submit="submit" class="mt-5 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" wire:model="name"
                               class="mt-1 w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('name')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Email</label>
                            <input type="email" wire:model="email"
                                   class="mt-1 w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                            @error('email')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Telepon</label>
                            <input type="text" wire:model="phone"
                                   class="mt-1 w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Subjek</label>
                        <input type="text" wire:model="subject"
                               class="mt-1 w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('subject')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Pesan</label>
                        <textarea wire:model="message" rows="5"
                                  class="mt-1 w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        @error('message')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-semibold transition disabled:opacity-60"
                            wire:loading.attr="disabled" wire:target="submit">
                        <x-heroicon-o-paper-airplane class="w-4 h-4" />
                        <span wire:loading.remove wire:target="submit">Kirim Pesan</span>
                        <span wire:loading wire:target="submit">Mengirim...</span>
                    </button>
                </form>
            </div>
        </div>
    </x-site.page-frame>
</div>
{{-- Nothing in the world is as soft and yielding as water. --}}
