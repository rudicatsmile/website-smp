{{-- Milleneal skin: page-frame. Receives $padded, $frameSlot, $frameAttributes. --}}
<div class="relative bg-pink-50 min-h-[40vh] pb-24 overflow-hidden">
    {{-- Decorative shapes --}}
    <div class="absolute top-20 left-10 w-64 h-64 bg-purple-200/40 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-20 right-10 w-72 h-72 bg-pink-200/40 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute inset-0 opacity-30 pointer-events-none" aria-hidden="true"
         style="background-image: radial-gradient(circle at 2px 2px, rgb(168 85 247 / 0.15) 2px, transparent 0); background-size: 32px 32px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div {{ ($frameAttributes ?? new \Illuminate\View\ComponentAttributeBag())->merge(['class' => 'bg-white rounded-[2rem] shadow-2xl shadow-purple-500/10 ring-1 ring-purple-100 '.(($padded ?? true) ? 'p-6 sm:p-8 lg:p-12' : '')]) }}>
            {!! $frameSlot ?? '' !!}
        </div>
    </div>
</div>
