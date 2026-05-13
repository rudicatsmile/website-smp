<div>
<x-site.page-hero
    key="alumni"
    title="Tracer Study Alumni"
    subtitle="Bantu sekolah berkembang dengan berbagi perjalananmu"
    icon="clipboard-document-list"
    :breadcrumbs="[['label' => 'Alumni', 'url' => route('alumni.index')], ['label' => 'Tracer Study']]"
/>

<x-site.page-frame :padded="true">
    <div class="max-w-2xl mx-auto">

        @if($submitted)
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-emerald-800 mb-2">Terima Kasih!</h2>
                <p class="text-emerald-700 mb-6">Respon Tracer Study kamu berhasil dikirimkan. Masukan kamu sangat berarti bagi kami.</p>
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <a href="{{ route('alumni.index') }}" wire:navigate
                       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition">
                        Lihat Profil Alumni
                    </a>
                    <button wire:click="$set('submitted', false)"
                            class="inline-flex items-center gap-2 border border-emerald-300 text-emerald-700 hover:bg-emerald-50 font-semibold px-5 py-2.5 rounded-xl transition">
                        Isi Lagi
                    </button>
                </div>
            </div>
        @else
            <form wire:submit="submit" class="space-y-8">

                {{-- Data Diri --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 text-xs font-bold">1</span>
                        Data Diri
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input wire:model="name" type="text" placeholder="Nama lengkap kamu"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('name') border-red-400 @enderror">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input wire:model="email" type="email" placeholder="email@example.com"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('email') border-red-400 @enderror">
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. HP / WhatsApp</label>
                            <input wire:model="phone" type="text" placeholder="08xxxxxxxxxx"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Lulus <span class="text-red-500">*</span></label>
                            <input wire:model="graduation_year" type="number" min="1990" max="{{ date('Y') }}" placeholder="{{ date('Y') - 3 }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('graduation_year') border-red-400 @enderror">
                            @error('graduation_year') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kota Domisili</label>
                            <input wire:model="city" type="text" placeholder="Jakarta"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                {{-- Aktivitas Saat Ini --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 text-xs font-bold">2</span>
                        Aktivitas Saat Ini
                    </h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach([
                                'working'      => ['Bekerja',          'heroicon-o-briefcase'],
                                'studying'     => ['Kuliah',           'heroicon-o-academic-cap'],
                                'entrepreneur' => ['Wirausaha',        'heroicon-o-building-storefront'],
                                'both'         => ['Kuliah & Bekerja', 'heroicon-o-star'],
                                'unemployed'   => ['Belum Bekerja',    'heroicon-o-clock'],
                                'other'        => ['Lainnya',          'heroicon-o-ellipsis-horizontal'],
                            ] as $val => [$label, $icon])
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="current_status" value="{{ $val }}" class="sr-only peer">
                                    <div class="border rounded-xl px-3 py-2.5 text-center text-sm font-medium transition
                                                peer-checked:bg-emerald-600 peer-checked:border-emerald-600 peer-checked:text-white
                                                border-slate-200 text-slate-600 hover:border-emerald-400">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('current_status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Perusahaan / Universitas</label>
                            <input wire:model="company_or_institution" type="text" placeholder="Nama perusahaan atau universitas"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan / Jurusan</label>
                            <input wire:model="position" type="text" placeholder="Programmer / Teknik Informatika"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kisaran Penghasilan / Uang Saku</label>
                            <select wire:model="income_range"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                                <option value="">-- Pilih --</option>
                                <option value="<2jt">< Rp 2 juta</option>
                                <option value="2-5jt">Rp 2 – 5 juta</option>
                                <option value="5-10jt">Rp 5 – 10 juta</option>
                                <option value="10-20jt">Rp 10 – 20 juta</option>
                                <option value=">20jt">> Rp 20 juta</option>
                                <option value="prefer_not_to_say">Tidak ingin menyebutkan</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Evaluasi Sekolah --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 text-xs font-bold">3</span>
                        Evaluasi Sekolah
                    </h2>

                    <div class="space-y-5">
                        {{-- Relevansi --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Seberapa relevan pendidikan SMP Al Wathoniyah 9 dengan perjalananmu?</label>
                            <div class="flex items-center gap-1" x-data="{ hoverRelevance: 0 }">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            wire:click="$set('school_relevance', {{ $i }})"
                                            x-on:mouseenter="hoverRelevance = {{ $i }}"
                                            x-on:mouseleave="hoverRelevance = 0"
                                            class="text-3xl transition focus:outline-none"
                                            :class="(hoverRelevance >= {{ $i }} || {{ $school_relevance ?? 0 }} >= {{ $i }}) ? 'text-yellow-400' : 'text-slate-300'">
                                        ★
                                    </button>
                                @endfor
                                @if($school_relevance)
                                    <span class="ml-2 text-sm text-slate-500">{{ $school_relevance }}/5</span>
                                @endif
                            </div>
                            @error('school_relevance') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Kualitas --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Bagaimana kualitas pendidikan SMP Al Wathoniyah 9 secara keseluruhan?</label>
                            <div class="flex items-center gap-1" x-data="{ hoverQuality: 0 }">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            wire:click="$set('school_quality', {{ $i }})"
                                            x-on:mouseenter="hoverQuality = {{ $i }}"
                                            x-on:mouseleave="hoverQuality = 0"
                                            class="text-3xl transition focus:outline-none"
                                            :class="(hoverQuality >= {{ $i }} || {{ $school_quality ?? 0 }} >= {{ $i }}) ? 'text-yellow-400' : 'text-slate-300'">
                                        ★
                                    </button>
                                @endfor
                                @if($school_quality)
                                    <span class="ml-2 text-sm text-slate-500">{{ $school_quality }}/5</span>
                                @endif
                            </div>
                            @error('school_quality') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Saran untuk Sekolah</label>
                            <textarea wire:model="suggestions" rows="4" placeholder="Apa yang ingin kamu sampaikan untuk kemajuan sekolah?"
                                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Izin Publikasi --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="allow_publish"
                               class="mt-0.5 w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="text-sm font-medium text-slate-700">Izinkan profil saya ditampilkan di halaman alumni publik</span>
                            <p class="text-xs text-slate-500 mt-0.5">Data sensitif (email, HP, penghasilan) tidak akan ditampilkan.</p>
                        </div>
                    </label>
                </div>

                <button type="submit"
                        wire:loading.attr="disabled"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-200">
                    <span wire:loading.remove>
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Tracer Study
                    </span>
                    <span wire:loading>Mengirim...</span>
                </button>
            </form>
        @endif
    </div>
</x-site.page-frame>
</div>
