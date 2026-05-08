@php
    $popups = \App\Models\Popup::active()->orderBy('order')->orderByDesc('id')->get();
    if ($popups->isEmpty()) {
        return;
    }
    $sizeMap = [
        'sm' => 'max-w-md',
        'md' => 'max-w-xl',
        'lg' => 'max-w-3xl',
        'xl' => 'max-w-5xl',
    ];
@endphp

<div
    x-data="popupQueue({
        popups: {{ Js::from($popups->map(fn ($p) => [
            'id' => $p->id,
            'updated_at' => $p->updated_at?->timestamp,
            'show_once' => (bool) $p->show_once,
        ])) }}
    })"
    x-init="init()"
    x-cloak
>
    @foreach($popups as $p)
        <template x-if="current && current.id === {{ $p->id }}">
            <div
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                role="dialog"
                aria-modal="true"
                aria-labelledby="popup-title-{{ $p->id }}"
                @keydown.escape.window="close()"
                x-transition.opacity
            >
                <div
                    @click.outside="close()"
                    class="relative w-full {{ $sizeMap[$p->size] ?? $sizeMap['lg'] }} max-h-[92vh] flex flex-col bg-white rounded-2xl shadow-2xl overflow-hidden"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                >
                    <button
                        type="button"
                        @click="close()"
                        aria-label="Tutup"
                        class="absolute top-3 right-3 z-10 w-8 h-8 grid place-items-center rounded-full bg-white/90 hover:bg-white text-slate-700 hover:text-slate-900 shadow ring-1 ring-slate-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    @if($p->image)
                        <div class="bg-slate-100 flex items-center justify-center overflow-hidden">
                            <img
                                src="{{ asset('storage/'.$p->image) }}"
                                alt="{{ $p->title }}"
                                class="w-full max-h-[70vh] object-contain"
                            >
                        </div>
                    @endif

                    @if($p->title || $p->content || $p->link_url)
                        <div class="px-5 py-4 sm:px-6 sm:py-5 border-t border-slate-100">
                            <h2 id="popup-title-{{ $p->id }}" class="text-base sm:text-lg font-semibold text-slate-900">{{ $p->title }}</h2>
                            @if($p->content)
                                <div class="prose prose-sm max-w-none mt-2 text-slate-600">{!! $p->content !!}</div>
                            @endif

                            <div class="mt-4 flex flex-row gap-2 justify-end">
                                <button
                                    type="button"
                                    @click="close()"
                                    class="inline-flex justify-center items-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-md text-sm font-medium"
                                >
                                    Tutup
                                </button>
                                @if($p->link_url)
                                    <a
                                        href="{{ $p->link_url }}"
                                        @click="close()"
                                        class="inline-flex justify-center items-center bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-md text-sm font-medium"
                                    >
                                        {{ $p->link_text ?? 'Selengkapnya' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </template>
    @endforeach
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('popupQueue', ({ popups }) => ({
                    queue: [],
                    current: null,
                    init() {
                        const seen = this.readSeen();
                        this.queue = popups.filter(p => {
                            if (! p.show_once) return true;
                            return seen[p.id] !== p.updated_at;
                        });
                        this.next();
                    },
                    next() {
                        this.current = this.queue.shift() || null;
                    },
                    close() {
                        if (this.current && this.current.show_once) {
                            this.markSeen(this.current);
                        }
                        this.next();
                    },
                    readSeen() {
                        try { return JSON.parse(localStorage.getItem('popups_seen') || '{}'); }
                        catch { return {}; }
                    },
                    markSeen(p) {
                        const seen = this.readSeen();
                        seen[p.id] = p.updated_at;
                        try { localStorage.setItem('popups_seen', JSON.stringify(seen)); } catch {}
                    },
                }));
            });
        </script>
    @endpush
@endonce
