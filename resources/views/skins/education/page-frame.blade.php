{{-- Education skin: page-frame partial. Receives $padded, $frameSlot, $frameAttributes. --}}
<div class="relative bg-slate-50 min-h-[40vh] pb-20"
     style="background-image: radial-gradient(circle at 1px 1px, rgb(100 116 139 / 0.08) 1px, transparent 0); background-size: 24px 24px;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative">
        <div {{ ($frameAttributes ?? new \Illuminate\View\ComponentAttributeBag())->merge(['class' => 'bg-white rounded-2xl shadow-xl ring-1 ring-slate-200/60 '.(($padded ?? true) ? 'p-6 sm:p-8 lg:p-10' : '')]) }}>
            {!! $frameSlot ?? '' !!}
        </div>
    </div>
</div>
