<div class="max-w-4xl mx-auto px-6 py-12">
    <a href="{{ route('akademik.index') }}" class="text-emerald-700 hover:underline text-sm">&larr; Kembali</a>
    <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ $academic->name }}</h1>
    @if($academic->head_name)<div class="text-emerald-700 mt-1">{{ $academic->head_name }}</div>@endif
    @if($academic->image)
        <img src="{{ asset('storage/'.$academic->image) }}" alt="" class="w-full rounded-xl mt-6">
    @endif
    @if($academic->description)
        <article class="prose max-w-none mt-6">{!! $academic->description !!}</article>
    @endif
    @if($academic->curriculum)
        <section class="mt-8">
            <h2 class="text-xl font-semibold text-slate-900">Kurikulum</h2>
            <div class="prose max-w-none mt-3">{!! $academic->curriculum !!}</div>
        </section>
    @endif
</div>
{{-- Close your eyes. Count to one. That is how long forever feels. --}}
