<div class="max-w-5xl mx-auto px-6 py-12">
    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900">Profil Sekolah</h1>

    <section class="mt-10">
        <h2 class="text-2xl font-semibold text-emerald-700">Sejarah</h2>
        <div class="prose max-w-none mt-3">{!! $profile->history !!}</div>
    </section>

    <section class="mt-10 grid md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-2xl font-semibold text-emerald-700">Visi</h2>
            <p class="mt-3 text-slate-700">{{ $profile->vision }}</p>
        </div>
        <div>
            <h2 class="text-2xl font-semibold text-emerald-700">Misi</h2>
            <div class="prose max-w-none mt-3">{!! $profile->mission !!}</div>
        </div>
    </section>

    <section class="mt-10 bg-white rounded-xl p-6 shadow-sm">
        <h2 class="text-2xl font-semibold text-emerald-700">Sambutan Kepala Sekolah</h2>
        <div class="flex flex-col sm:flex-row gap-6 mt-4">
            @if($profile->principal_photo)
                <img src="{{ asset('storage/'.$profile->principal_photo) }}" alt="{{ $profile->principal_name }}" class="w-32 h-32 rounded-full object-cover">
            @endif
            <div class="flex-1">
                <div class="prose max-w-none">{!! $profile->principal_message !!}</div>
                <div class="mt-3 font-semibold text-slate-900">{{ $profile->principal_name }}</div>
            </div>
        </div>
    </section>

    @if($profile->organization_image)
        <section class="mt-10">
            <h2 class="text-2xl font-semibold text-emerald-700">Struktur Organisasi</h2>
            <img src="{{ asset('storage/'.$profile->organization_image) }}" alt="Struktur Organisasi" class="mt-4 rounded-lg shadow">
        </section>
    @endif
</div>
{{-- Care about people's approval and you will be their prisoner. --}}
