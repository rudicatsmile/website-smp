<div>
    <x-site.page-hero
        key="faq"
        title="FAQ - Tanya Jawab"
        subtitle="Temukan jawaban untuk pertanyaan umum seputar PPDB, biaya, fasilitas, dan lainnya."
        icon="chat-bubble-left-right"
        :breadcrumbs="[['label' => 'FAQ']]"
    />

    <x-site.page-frame>
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Chat Panel --}}
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden sticky top-20">
                    <div class="bg-emerald-600 text-white px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                                <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="font-semibold text-sm">Tanya Bot FAQ</div>
                                <div class="text-xs text-emerald-100">Online 24/7</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 h-[400px] overflow-y-auto space-y-3 bg-slate-50" id="faq-chat-messages">
                        @if(empty($messages))
                            <div class="text-center text-slate-400 text-sm py-8">
                                <x-heroicon-o-chat-bubble-left-right class="w-10 h-10 mx-auto mb-2 text-slate-300" />
                                <p>Silakan ketik pertanyaan Anda di bawah.</p>
                                <p class="text-xs mt-1">Contoh: "Biaya SPP berapa?", "Cara daftar PPDB?"</p>
                            </div>
                        @endif
                        @foreach($messages as $msg)
                            @if($msg['type'] === 'user')
                                <div class="flex justify-end">
                                    <div class="bg-emerald-600 text-white px-4 py-2.5 rounded-2xl rounded-br-md max-w-[80%] text-sm leading-relaxed">
                                        {{ $msg['text'] }}
                                    </div>
                                </div>
                            @else
                                <div class="flex gap-2.5">
                                    <div class="w-7 h-7 bg-emerald-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                        <x-heroicon-o-chat-bubble-left-right class="w-3.5 h-3.5 text-emerald-700" />
                                    </div>
                                    <div class="max-w-[85%]">
                                        <div class="bg-white px-4 py-2.5 rounded-2xl rounded-bl-md ring-1 ring-slate-200 text-sm leading-relaxed whitespace-pre-line">
                                            {!! nl2br(e($msg['text'])) !!}
                                        </div>
                                        @if(!empty($msg['results']) && count($msg['results']) > 1)
                                            <div class="mt-2 space-y-1.5">
                                                @foreach($msg['results'] as $i => $result)
                                                    <div class="px-3 py-2 bg-white rounded-lg ring-1 ring-slate-200 text-xs">
                                                        <span class="font-medium text-slate-800">{{ $i + 1 }}. {{ $result['question'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if(($msg['responseType'] ?? '') === 'fallback')
                                            <div class="mt-2 flex items-center gap-2">
                                                <button wire:click="markHelpful" class="text-xs px-2.5 py-1 bg-slate-100 hover:bg-emerald-100 rounded-full text-slate-500 hover:text-emerald-700 transition">
                                                    Membantu
                                                </button>
                                                <button wire:click="markUnhelpful" class="text-xs px-2.5 py-1 bg-slate-100 hover:bg-red-50 rounded-full text-slate-500 hover:text-red-600 transition">
                                                    Tidak
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="p-3 bg-white border-t border-slate-200">
                        <form wire:submit="sendMessage" class="flex gap-2">
                            <input type="text" wire:model="message"
                                   placeholder="Ketik pertanyaan..."
                                   class="flex-1 px-4 py-2.5 text-sm rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 bg-slate-50"
                                   autocomplete="off">
                            <button type="submit"
                                    class="shrink-0 w-10 h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl flex items-center justify-center transition disabled:opacity-50"
                                    wire:loading.attr="disabled" wire:target="sendMessage">
                                <x-heroicon-o-paper-airplane class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- FAQ List --}}
            <div class="lg:col-span-2 order-1 lg:order-2">
                <div class="flex flex-wrap gap-2 mb-6">
                    <button wire:click="filterByCategory(null)"
                            class="px-4 py-2 text-sm font-medium rounded-full transition @if(!$activeCategory) bg-emerald-600 text-white @else bg-slate-100 text-slate-600 hover:bg-slate-200 @endif">
                        Semua
                    </button>
                    @foreach($categories as $cat)
                        <button wire:click="filterByCategory('{{ $cat }}')"
                                class="px-4 py-2 text-sm font-medium rounded-full transition @if($activeCategory === $cat) bg-emerald-600 text-white @else bg-slate-100 text-slate-600 hover:bg-slate-200 @endif">
                            {{ ucfirst($cat) }}
                        </button>
                    @endforeach
                </div>

                @if($faqs->isEmpty())
                    <div class="text-center py-16 text-slate-400">
                        <x-heroicon-o-magnifying-glass class="w-12 h-12 mx-auto mb-3 text-slate-300" />
                        <p class="font-medium">Belum ada FAQ untuk kategori ini.</p>
                    </div>
                @else
                    <div class="space-y-8">
                        @foreach($faqs as $category => $items)
                            <div>
                                <h2 class="text-lg font-semibold text-emerald-700 flex items-center gap-2 mb-4">
                                    <span class="inline-block w-1.5 h-5 bg-emerald-600 rounded"></span>
                                    {{ ucfirst($category) }}
                                </h2>
                                <div class="space-y-3">
                                    @foreach($items as $faq)
                                        <details class="group bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
                                            <summary class="px-5 py-4 cursor-pointer flex items-center justify-between gap-3 hover:bg-slate-50 transition list-none">
                                                <span class="text-sm font-medium text-slate-800">{{ $faq->question }}</span>
                                                <x-heroicon-o-chevron-down class="w-4 h-4 text-slate-400 shrink-0 group-open:rotate-180 transition-transform" />
                                            </summary>
                                            <div class="px-5 pb-4 text-sm text-slate-600 leading-relaxed">
                                                {!! nl2br(e($faq->answer)) !!}
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-site.page-frame>
</div>