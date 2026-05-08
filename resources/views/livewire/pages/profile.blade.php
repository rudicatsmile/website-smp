<div>
    <x-site.page-hero
        key="profil"
        title="Profil Sekolah"
        subtitle="Mengenal lebih dekat visi, misi, sejarah, dan keluarga besar kami."
        icon="building-office-2"
        :breadcrumbs="[['label' => 'Profil']]"
    />

    <x-site.page-frame>
        <section>
            <h2 class="text-2xl font-semibold text-emerald-700 flex items-center gap-2">
                <span class="inline-block w-1.5 h-6 bg-emerald-600 rounded"></span>
                Sejarah
            </h2>
            <div class="prose max-w-none mt-4">{!! $profile->history !!}</div>
        </section>

        <div class="grid md:grid-cols-2 gap-6 mt-10">
            <section class="bg-emerald-50 rounded-xl p-6 ring-1 ring-emerald-100">
                <h2 class="text-xl font-semibold text-emerald-700 flex items-center gap-2">
                    <span class="inline-block w-1.5 h-5 bg-emerald-600 rounded"></span>
                    Visi
                </h2>
                <p class="mt-3 text-slate-700 leading-relaxed">{{ $profile->vision }}</p>
            </section>
            <section class="bg-slate-50 rounded-xl p-6 ring-1 ring-slate-200">
                <h2 class="text-xl font-semibold text-emerald-700 flex items-center gap-2">
                    <span class="inline-block w-1.5 h-5 bg-emerald-600 rounded"></span>
                    Misi
                </h2>
                <div class="prose max-w-none mt-3">{!! $profile->mission !!}</div>
            </section>
        </div>

        <section class="mt-10 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-6 sm:p-8 ring-1 ring-emerald-100">
            <h2 class="text-2xl font-semibold text-emerald-700 flex items-center gap-2">
                <span class="inline-block w-1.5 h-6 bg-emerald-600 rounded"></span>
                Sambutan Kepala Sekolah
            </h2>
            <div class="flex flex-col sm:flex-row gap-6 mt-5">
                @if($profile->principal_photo)
                    <img src="{{ asset('storage/'.$profile->principal_photo) }}"
                         alt="{{ $profile->principal_name }}"
                         class="w-32 h-32 rounded-full object-cover ring-4 ring-white shadow-lg shrink-0">
                @endif
                <div class="flex-1">
                    <div class="prose max-w-none">{!! $profile->principal_message !!}</div>
                    <div class="mt-4 font-semibold text-slate-900">{{ $profile->principal_name }}</div>
                    <div class="text-sm text-emerald-700">Kepala Sekolah</div>
                </div>
            </div>
        </section>

        @if($profile->organization_image)
            <section class="mt-10">
                <h2 class="text-2xl font-semibold text-emerald-700 flex items-center gap-2">
                    <span class="inline-block w-1.5 h-6 bg-emerald-600 rounded"></span>
                    Struktur Organisasi
                </h2>
                <div class="mt-4 rounded-xl overflow-hidden ring-1 ring-slate-200">
                    <img src="{{ asset('storage/'.$profile->organization_image) }}"
                         alt="Struktur Organisasi"
                         class="w-full">
                </div>
            </section>
        @endif
    </x-site.page-frame>
</div>
{{-- Care about people's approval and you will be their prisoner. --}}
