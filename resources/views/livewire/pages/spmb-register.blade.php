@php
    $skin = app(\App\Settings\GeneralSettings::class)->active_skin ?: 'education';
    $skinTokens = $skin === 'milleneal' ? [
        'gradient' => 'from-pink-500 to-purple-600',
        'gradientHover' => 'hover:from-pink-600 hover:to-purple-700',
        'gradientText' => 'from-pink-600 to-purple-700',
        'gradientStrong' => 'from-pink-600 to-purple-700',
        'gradientStrongHover' => 'hover:from-pink-700 hover:to-purple-800',
        'ring' => 'focus:ring-pink-200',
        'border' => 'focus:border-pink-500',
        'borderActive' => 'border-pink-400',
        'bgSoft' => 'bg-pink-50/40',
        'bgHover' => 'hover:bg-pink-50/40',
        'borderHover' => 'hover:border-pink-400',
        'text' => 'text-pink-700',
        'textDark' => 'text-pink-900',
        'shadow' => 'shadow-pink-200',
        'fileBg' => 'file:bg-pink-100 file:text-pink-700 hover:file:bg-pink-200',
        'badge' => 'bg-pink-500',
        'borderSoft' => 'border-pink-200',
    ] : [
        'gradient' => 'from-emerald-500 to-teal-600',
        'gradientHover' => 'hover:from-emerald-600 hover:to-teal-700',
        'gradientText' => 'from-emerald-600 to-teal-700',
        'gradientStrong' => 'from-emerald-600 to-teal-700',
        'gradientStrongHover' => 'hover:from-emerald-700 hover:to-teal-800',
        'ring' => 'focus:ring-emerald-200',
        'border' => 'focus:border-emerald-500',
        'borderActive' => 'border-emerald-400',
        'bgSoft' => 'bg-emerald-50/40',
        'bgHover' => 'hover:bg-emerald-50/30',
        'borderHover' => 'hover:border-emerald-400',
        'text' => 'text-emerald-700',
        'textDark' => 'text-emerald-900',
        'shadow' => 'shadow-emerald-200',
        'fileBg' => 'file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200',
        'badge' => 'bg-emerald-500',
        'borderSoft' => 'border-emerald-200',
    ];
@endphp
<div>
    <x-site.page-hero
        title="Pendaftaran SPMB"
        :subtitle="$period?->name ?? 'Lengkapi formulir berikut dengan data yang benar dan valid'"
        icon="academic-cap"
    />

    <x-site.page-frame>
        <div class="max-w-4xl mx-auto">
        {{-- Stepper --}}
        @if($step < 4)
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-6">
                <div class="flex items-center justify-between">
                    @php
                        $steps = [
                            1 => ['label' => 'Data Diri', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            2 => ['label' => 'Orang Tua & Sekolah', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            3 => ['label' => 'Dokumen', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                        ];
                    @endphp
                    @foreach($steps as $n => $s)
                        <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="relative">
                                    <div @class(['w-12 h-12 rounded-full grid place-items-center text-sm font-bold transition-all duration-300', 'bg-gradient-to-br '.$skinTokens['gradient'].' text-white shadow-lg' => $step > $n, 'bg-gradient-to-br '.$skinTokens['gradient'].' text-white shadow-lg ring-4 '.($skin === 'milleneal' ? 'ring-pink-200' : 'ring-emerald-200') => $step === $n, 'bg-slate-100 text-slate-400' => $step < $n])>
                                        @if($step > $n)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-xs mt-2 font-bold {{ $step >= $n ? $skinTokens['text'] : 'text-slate-400' }} hidden sm:block">{{ $s['label'] }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-1 mx-2 rounded {{ $step > $n ? 'bg-gradient-to-r '.$skinTokens['gradient'] : 'bg-slate-200' }} transition-all duration-500"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Form Card --}}
        <form wire:submit.prevent="{{ $step === 3 ? 'submit' : 'next' }}" class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            {{-- STEP 1: Data Diri --}}
            @if($step === 1)
                <div class="bg-gradient-to-r {{ $skinTokens['gradientStrong'] }} px-8 py-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-white">Data Diri Calon Siswa</h2>
                            <p class="text-white/80 text-sm">Isi data pribadi sesuai dokumen resmi</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 grid sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="full_name" placeholder="Sesuai akta kelahiran" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                        @error('full_name')<div class="text-rose-600 text-xs mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Panggilan</label>
                        <input type="text" wire:model="nick_name" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select wire:model="gender" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="birth_place" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                        @error('birth_place')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="birth_date" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                        @error('birth_date')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">NIK <span class="text-slate-400 font-normal text-xs">(16 digit)</span></label>
                        <input type="text" wire:model="nik" maxlength="16" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">NISN</label>
                        <input type="text" wire:model="nisn" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Agama <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="religion" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea wire:model="address" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all"></textarea>
                        @error('address')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">No. HP / WhatsApp</label>
                        <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Email</label>
                        <input type="email" wire:model="email" placeholder="email@contoh.com" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                        @error('email')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

            {{-- STEP 2: Orang Tua & Sekolah --}}
            @elseif($step === 2)
                <div class="bg-gradient-to-r {{ $skinTokens['gradientStrong'] }} px-8 py-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-white">Data Orang Tua & Asal Sekolah</h2>
                            <p class="text-white/80 text-sm">Isi data orang tua dan sekolah asal</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 space-y-6">
                    {{-- Ayah --}}
                    <div class="bg-blue-50 rounded-2xl p-5 border border-blue-100">
                        <h3 class="text-sm font-extrabold text-blue-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Data Ayah
                        </h3>
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Ayah <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="father_name" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                                @error('father_name')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Pekerjaan</label>
                                <input type="text" wire:model="father_job" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">No. HP</label>
                                <input type="text" wire:model="father_phone" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                            </div>
                        </div>
                    </div>
                    {{-- Ibu --}}
                    <div class="bg-pink-50 rounded-2xl p-5 border border-pink-100">
                        <h3 class="text-sm font-extrabold text-pink-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Data Ibu
                        </h3>
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Ibu <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="mother_name" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                                @error('mother_name')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Pekerjaan</label>
                                <input type="text" wire:model="mother_job" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">No. HP</label>
                                <input type="text" wire:model="mother_phone" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                            </div>
                        </div>
                    </div>
                    {{-- Wali --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Wali <span class="text-slate-400 font-normal">(jika ada)</span></label>
                        <input type="text" wire:model="guardian_name" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all">
                    </div>
                    {{-- Asal Sekolah --}}
                    <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100">
                        <h3 class="text-sm font-extrabold text-emerald-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                            Data Asal Sekolah
                        </h3>
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Asal Sekolah <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="previous_school" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                                @error('previous_school')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Tahun Lulus <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="graduation_year" placeholder="2026" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                                @error('graduation_year')<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">NPSN</label>
                                <input type="text" wire:model="npsn" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 {{ $skinTokens['border'] }} focus:ring-2 {{ $skinTokens['ring'] }} transition-all bg-white">
                            </div>
                        </div>
                    </div>
                </div>

            {{-- STEP 3: Dokumen --}}
            @elseif($step === 3)
                <div class="bg-gradient-to-r {{ $skinTokens['gradientStrong'] }} px-8 py-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-white">Upload Dokumen Pendukung</h2>
                            <p class="text-white/80 text-sm">Format: PDF, JPG, PNG (maks. 5 MB / file, foto maks. 2 MB)</p>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    {{-- Info Banner --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/></svg>
                        <div class="text-sm text-blue-900">
                            <p class="font-bold mb-1">Petunjuk Upload Dokumen</p>
                            <ul class="list-disc list-inside space-y-0.5 text-blue-800">
                                <li>Pastikan dokumen jelas dan terbaca</li>
                                <li>Format file: PDF, JPG, atau PNG</li>
                                <li>Ukuran maksimal: 5 MB per dokumen (foto maks. 2 MB)</li>
                                <li>Pas foto dengan background merah, ukuran 3x4</li>
                            </ul>
                        </div>
                    </div>

                    @php
                        $docs = [
                            ['kk', 'Kartu Keluarga (KK)', 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', 'PDF, JPG, PNG (Maks. 5MB)'],
                            ['akta', 'Akta Kelahiran', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'PDF, JPG, PNG (Maks. 5MB)'],
                            ['foto', 'Pas Foto', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', '3x4 Background Merah, JPG/PNG (Maks. 2MB)'],
                            ['ijazah', 'Ijazah / SKL', 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'PDF, JPG, PNG (Maks. 5MB)'],
                            ['raport', 'Rapor SD/MI', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'PDF, JPG, PNG (Maks. 5MB)'],
                        ];
                    @endphp

                    {{-- Progress Summary --}}
                    @php
                        $uploadedCount = collect(['kk','akta','foto','ijazah','raport'])->filter(fn($k) => $this->{'doc_'.$k} !== null)->count();
                        $totalDocs = count($docs);
                        $progress = $totalDocs > 0 ? round(($uploadedCount / $totalDocs) * 100) : 0;
                    @endphp
                    <div class="bg-gradient-to-r from-{{ $skin === 'milleneal' ? 'pink' : 'emerald' }}-50 to-{{ $skin === 'milleneal' ? 'purple' : 'teal' }}-50 border {{ $skinTokens['borderSoft'] }} rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold {{ $skinTokens['textDark'] }}">Progress Upload</span>
                            <span class="text-sm font-extrabold {{ $skinTokens['text'] }}">{{ $uploadedCount }} / {{ $totalDocs }} dokumen</span>
                        </div>
                        <div class="w-full bg-white rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r {{ $skinTokens['gradient'] }} h-full rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-4">
                    @foreach($docs as $d)
                        @php
                            $key = $d[0];
                            $prop = 'doc_' . $key;
                            $file = $this->{$prop};
                            $isUploaded = $file !== null;
                            $isImage = $isUploaded && method_exists($file, 'temporaryUrl') && str_starts_with($file->getMimeType() ?? '', 'image/');
                        @endphp
                        <div @class(['rounded-2xl border-2 transition-all overflow-hidden', $skinTokens['borderActive'].' '.$skinTokens['bgSoft'] => $isUploaded, 'border-dashed border-slate-200 '.$skinTokens['borderHover'].' '.$skinTokens['bgHover'] => !$isUploaded])>
                            <div class="p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-gradient-to-br {{ $skinTokens['gradient'] }} rounded-xl flex items-center justify-center shadow-md flex-shrink-0 transition-all">
                                        @if($isUploaded)
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $d[2] }}"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3 flex-wrap">
                                            <div>
                                                <h4 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                                                    {{ $d[1] }}
                                                    @if($isUploaded)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $skinTokens['badge'] }} text-white">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                            Terupload
                                                        </span>
                                                    @endif
                                                </h4>
                                                <p class="text-xs text-slate-500 mt-0.5">{{ $d[3] }}</p>
                                            </div>
                                            @if(!$isUploaded)
                                                <label for="file_{{ $key }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm cursor-pointer bg-gradient-to-r {{ $skinTokens['gradientStrong'] }} {{ $skinTokens['gradientStrongHover'] }} text-white shadow-md hover:shadow-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                    Pilih File
                                                </label>
                                            @endif
                                        </div>

                                        <input id="file_{{ $key }}" type="file" wire:model="doc_{{ $key }}" class="hidden" accept="{{ $key === 'foto' ? '.jpg,.jpeg,.png' : '.pdf,.jpg,.jpeg,.png' }}">

                                        {{-- Loading State --}}
                                        <div wire:loading wire:target="doc_{{ $key }}" class="mt-3 flex items-center gap-2 text-sm {{ $skinTokens['text'] }} font-medium">
                                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                                            Mengupload file...
                                        </div>

                                        {{-- Uploaded File Preview --}}
                                        @if($isUploaded)
                                            <div wire:loading.remove wire:target="doc_{{ $key }}" class="mt-3 bg-white border {{ $skinTokens['borderSoft'] }} rounded-xl p-3 flex items-center gap-3">
                                                @if($isImage)
                                                    <img src="{{ $file->temporaryUrl() }}" alt="preview" class="w-12 h-12 object-cover rounded-lg border border-slate-200">
                                                @else
                                                    <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-slate-800 truncate">{{ method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : 'File terupload' }}</p>
                                                    @if(method_exists($file, 'getSize'))
                                                        <p class="text-xs text-slate-500">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <label for="file_{{ $key }}" class="px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                        Ganti
                                                    </label>
                                                    <button type="button" wire:click="removeDoc('{{ $key }}')" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-100 hover:bg-rose-200 text-rose-700 transition-colors flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        @error('doc_'.$key)
                                            <div class="mt-2 text-rose-600 text-xs flex items-center gap-1 font-medium">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>

            {{-- STEP 4: Success --}}
            @elseif($step === 4)
                <div class="p-12 text-center">
                    <div class="relative inline-block">
                        <div class="w-24 h-24 bg-gradient-to-br {{ $skinTokens['gradient'] }} rounded-full flex items-center justify-center shadow-2xl {{ $skinTokens['shadow'] }} mx-auto">
                            <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center animate-bounce">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.5 7.5L22 12l-7.5 2.5L12 22l-2.5-7.5L2 12l7.5-2.5z"/></svg>
                        </div>
                    </div>
                    <h2 class="text-3xl font-extrabold text-slate-900 mt-6">Pendaftaran Berhasil!</h2>
                    <p class="text-slate-600 mt-2">Terima kasih telah mendaftar di sekolah kami</p>
                    @if($created)
                        <div class="bg-gradient-to-br from-{{ $skin === 'milleneal' ? 'pink' : 'emerald' }}-50 to-{{ $skin === 'milleneal' ? 'purple' : 'teal' }}-50 rounded-2xl p-6 mt-6 border-2 {{ $skinTokens['borderSoft'] }} inline-block">
                            <p class="text-sm text-slate-600 font-medium">Nomor Pendaftaran Anda</p>
                            <div class="text-3xl font-extrabold bg-gradient-to-r {{ $skinTokens['gradientText'] }} bg-clip-text text-transparent mt-2 tracking-wider">{{ $created->registration_number }}</div>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 text-left max-w-md mx-auto">
                            <p class="text-sm text-amber-900 flex items-start gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/></svg>
                                <span><strong>Penting:</strong> Simpan nomor pendaftaran ini untuk cek status. Email konfirmasi telah dikirim ke alamat email Anda.</span>
                            </p>
                        </div>
                    @endif
                    <a href="{{ route('spmb.status') }}" class="inline-flex items-center gap-2 mt-8 bg-gradient-to-r {{ $skinTokens['gradientStrong'] }} {{ $skinTokens['gradientStrongHover'] }} text-white px-8 py-3.5 rounded-xl font-bold shadow-lg {{ $skinTokens['shadow'] }} hover:shadow-xl transition-all">
                        Cek Status Pendaftaran
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            @endif

            {{-- Navigation --}}
            @if($step < 4)
                <div class="flex justify-between items-center px-8 py-6 bg-slate-50 border-t border-slate-100">
                    @if($step > 1)
                        <button type="button" wire:click="back" class="px-6 py-2.5 rounded-xl border-2 border-slate-200 text-slate-700 font-semibold hover:bg-white hover:border-slate-300 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </button>
                    @else
                        <span></span>
                    @endif
                    <button type="submit" wire:loading.attr="disabled" class="bg-gradient-to-r {{ $skinTokens['gradientStrong'] }} {{ $skinTokens['gradientStrongHover'] }} text-white px-12 py-3 rounded-xl font-bold shadow-lg {{ $skinTokens['shadow'] }} hover:shadow-xl transition-all flex items-center justify-center gap-2 disabled:opacity-60 min-w-[200px]">
                        <span wire:loading.remove wire:target="{{ $step === 3 ? 'submit' : 'next' }}">
                            {{ $step === 3 ? 'Submit Pendaftaran' : 'Lanjut' }}
                        </span>
                        <span wire:loading wire:target="{{ $step === 3 ? 'submit' : 'next' }}" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                            Memproses...
                        </span>
                        <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @endif
        </form>

        {{-- Help Card --}}
        @if($step < 4)
            <div class="mt-6 bg-white rounded-2xl p-5 shadow-md border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-800">Butuh Bantuan?</h4>
                    <p class="text-sm text-slate-600">Hubungi panitia SPMB melalui WhatsApp untuk pertanyaan seputar pendaftaran.</p>
                </div>
            </div>
        @endif
        </div>
    </x-site.page-frame>
</div>
