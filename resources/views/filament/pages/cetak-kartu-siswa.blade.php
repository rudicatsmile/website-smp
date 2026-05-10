<x-filament-panels::page>
    @php
        $classes = \App\Models\SchoolClass::where('is_active', true)->orderBy('grade')->orderBy('section')->get();
        $classStudents = $this->classStudents;
        $selected = $this->selectedStudents;
        $settings = app(\App\Settings\GeneralSettings::class);
        $selectedCount = count($student_ids);
        $totalInClass = $classStudents->count();
        $allSelected = $totalInClass > 0 && $selectedCount === $totalInClass;
    @endphp

    {{-- HERO HEADER --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-600 text-white p-6 shadow-lg">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center ring-2 ring-white/30">
                    <svg style="width:26px;height:26px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight">Cetak Kartu Pelajar</h2>
                    <p class="text-sm text-white/80 mt-1">Generate kartu siswa berisi QR code untuk absensi digital. Cetak A4, lalu potong & laminating.</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-center">
                <div class="bg-white/15 backdrop-blur rounded-xl px-4 py-2 ring-1 ring-white/20">
                    <div class="text-[10px] font-bold uppercase tracking-wider opacity-80">Dipilih</div>
                    <div class="text-2xl font-extrabold">{{ $selectedCount }}</div>
                </div>
                <div class="bg-white/15 backdrop-blur rounded-xl px-4 py-2 ring-1 ring-white/20">
                    <div class="text-[10px] font-bold uppercase tracking-wider opacity-80">Total Kelas</div>
                    <div class="text-2xl font-extrabold">{{ $totalInClass }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- STEP 1: PILIH KELAS --}}
    <div class="rounded-2xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-5 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-extrabold flex items-center justify-center text-sm ring-2 ring-indigo-200 dark:ring-indigo-800">1</div>
            <div>
                <div class="text-sm font-extrabold text-gray-800 dark:text-gray-100">Pilih Kelas</div>
                <div class="text-xs text-gray-500">Tentukan kelas yang akan dicetak kartunya.</div>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
            @foreach($classes as $c)
                @php $active = $school_class_id === $c->id; @endphp
                <button type="button"
                        wire:click="setClass({{ $c->id }})"
                        class="group relative rounded-xl border-2 px-3 py-3 text-left transition shadow-sm
                            {{ $active
                                ? 'border-indigo-500 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/30 dark:to-blue-900/30 ring-2 ring-indigo-500/30'
                                : 'border-gray-200 dark:border-white/10 hover:border-indigo-300 hover:bg-indigo-50/40 dark:hover:bg-white/10' }}">
                    <div class="text-[10px] font-bold uppercase tracking-wider {{ $active ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-500' }}">Kelas</div>
                    <div class="text-lg font-extrabold {{ $active ? 'text-indigo-900 dark:text-indigo-100' : 'text-gray-800 dark:text-gray-100' }}">{{ $c->name }}</div>
                    <div class="text-[10px] text-gray-500">{{ $c->academic_year }}</div>
                    @if($active)
                        <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                            <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- STEP 2: PILIH SISWA --}}
    @if($classStudents->isNotEmpty())
        <div class="rounded-2xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3 mb-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-extrabold flex items-center justify-center text-sm ring-2 ring-indigo-200 dark:ring-indigo-800">2</div>
                    <div>
                        <div class="text-sm font-extrabold text-gray-800 dark:text-gray-100">Pilih Siswa</div>
                        <div class="text-xs text-gray-500">{{ $selectedCount }} dari {{ $totalInClass }} siswa terpilih.</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($allSelected)
                        <button type="button" wire:click="clearSelection"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-3 py-1.5 text-xs shadow-sm">
                            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Kosongkan
                        </button>
                    @else
                        <button type="button" wire:click="selectAll"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-3 py-1.5 text-xs shadow-sm">
                            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Pilih Semua
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                @foreach($classStudents as $s)
                    @php $isSel = in_array($s->id, $student_ids, true); @endphp
                    <button type="button" wire:click="toggleStudent({{ $s->id }})"
                            class="group relative rounded-xl border-2 p-3 text-left transition shadow-sm
                                {{ $isSel
                                    ? 'border-indigo-500 bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-900/30 dark:to-transparent ring-1 ring-indigo-500/20'
                                    : 'border-gray-200 dark:border-white/10 hover:border-indigo-300 hover:shadow' }}">
                        <div class="flex items-center gap-2.5">
                            @if($s->photo_url)
                                <img src="{{ $s->photo_url }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center font-extrabold ring-2 ring-white shadow">{{ mb_substr($s->name, 0, 1) }}</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-gray-800 dark:text-gray-100 truncate">{{ $s->name }}</div>
                                <div class="text-[10px] text-gray-500 font-mono">{{ $s->nis }}</div>
                            </div>
                        </div>
                        @if($isSel)
                            <div class="absolute top-1.5 right-1.5 w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow">
                                <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-2xl border-2 border-dashed border-gray-300 dark:border-white/10 p-10 text-center text-gray-500 text-sm">
            Pilih kelas di atas untuk menampilkan daftar siswa.
        </div>
    @endif

    {{-- STEP 3: PRATINJAU & UNDUH --}}
    @if($selectedCount > 0)
        <div class="rounded-2xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3 mb-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-extrabold flex items-center justify-center text-sm ring-2 ring-emerald-200 dark:ring-emerald-800">3</div>
                    <div>
                        <div class="text-sm font-extrabold text-gray-800 dark:text-gray-100">Pratinjau & Cetak</div>
                        <div class="text-xs text-gray-500">{{ $selectedCount }} kartu siap dicetak. Format A4, 2 kartu per baris.</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @unless($showPreview)
                        <button type="button" wire:click="preview"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-white border border-indigo-300 hover:bg-indigo-50 text-indigo-700 font-semibold px-4 py-2 text-sm shadow-sm">
                            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Pratinjau
                        </button>
                    @endunless
                    <button type="button" wire:click="downloadPdf"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold px-4 py-2 text-sm shadow-md">
                        <svg wire:loading.remove wire:target="downloadPdf" style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <svg wire:loading wire:target="downloadPdf" style="width:15px;height:15px;" class="animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span wire:loading.remove wire:target="downloadPdf">Unduh PDF</span>
                        <span wire:loading wire:target="downloadPdf">Membuat PDF...</span>
                    </button>
                </div>
            </div>

            @if($showPreview && $selected->isNotEmpty())
                <div class="rounded-xl bg-slate-100 dark:bg-slate-900/40 p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($selected as $s)
                            @php
                                if (! $s->qr_token) { $s->generateQrToken(); }
                                $qr = \App\Filament\Pages\CetakKartuSiswa::makeQrDataUri($s->qr_token);
                            @endphp
                            {{-- ID Card preview (CR80 ratio 85:54) --}}
                            <div class="relative aspect-[85/54] rounded-2xl overflow-hidden shadow-xl ring-1 ring-black/10
                                        bg-gradient-to-br from-indigo-700 via-blue-700 to-cyan-700 text-white">
                                {{-- decorative blob --}}
                                <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-cyan-400/20 rounded-full blur-2xl"></div>
                                {{-- diagonal accent --}}
                                <div class="absolute inset-x-0 bottom-0 h-1.5 bg-gradient-to-r from-amber-400 via-rose-400 to-pink-500"></div>

                                <div class="relative h-full flex flex-col p-3.5">
                                    {{-- header --}}
                                    <div class="flex items-center gap-2 pb-2 border-b border-white/20">
                                        @if($settings->logo)
                                            <img src="{{ asset('storage/'.$settings->logo) }}" class="w-7 h-7 rounded bg-white/90 p-0.5">
                                        @else
                                            <div class="w-7 h-7 rounded bg-white text-indigo-700 font-extrabold flex items-center justify-center text-xs">S</div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[9px] uppercase tracking-widest opacity-75 leading-tight">Kartu Pelajar</div>
                                            <div class="text-[11px] font-extrabold tracking-tight leading-tight truncate">{{ $settings->school_name }}</div>
                                        </div>
                                    </div>

                                    {{-- body --}}
                                    <div class="flex-1 flex items-center gap-3 pt-2.5">
                                        @if($s->photo_url)
                                            <img src="{{ $s->photo_url }}" class="w-16 h-20 rounded-lg object-cover ring-2 ring-white/40 shadow-lg">
                                        @else
                                            <div class="w-16 h-20 rounded-lg bg-white/15 ring-2 ring-white/40 flex items-center justify-center text-3xl font-extrabold">{{ mb_substr($s->name, 0, 1) }}</div>
                                        @endif
                                        <div class="flex-1 min-w-0 space-y-0.5">
                                            <div class="text-[15px] font-extrabold leading-tight tracking-tight truncate">{{ $s->name }}</div>
                                            <div class="grid grid-cols-[60px_1fr] gap-x-1.5 gap-y-0 text-[10px] mt-1">
                                                <span class="opacity-70">NIS</span><span class="font-bold font-mono">{{ $s->nis }}</span>
                                                @if($s->nisn)<span class="opacity-70">NISN</span><span class="font-mono">{{ $s->nisn }}</span>@endif
                                                <span class="opacity-70">Kelas</span><span class="font-bold">{{ $s->schoolClass?->name ?? '—' }}</span>
                                                @if($s->birth_date)
                                                    <span class="opacity-70">Lahir</span><span class="text-[9px]">{{ $s->birth_place ? $s->birth_place.', ' : '' }}{{ $s->birth_date->translatedFormat('d M Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="bg-white p-1 rounded shadow">
                                                <img src="{{ $qr }}" class="w-[68px] h-[68px] block">
                                            </div>
                                            <div class="text-[7px] mt-0.5 opacity-75 font-mono tracking-wider">{{ $s->qr_token }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-xl border-2 border-dashed border-gray-300 dark:border-white/10 p-8 text-center text-sm text-gray-500">
                    Klik <span class="font-bold text-indigo-600">Pratinjau</span> untuk melihat tampilan kartu, atau langsung <span class="font-bold text-emerald-600">Unduh PDF</span>.
                </div>
            @endif
        </div>
    @endif

    {{-- TIPS --}}
    <div class="rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 dark:from-amber-900/20 dark:to-orange-900/20 dark:border-amber-800/50 p-4">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 flex items-center justify-center flex-shrink-0">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="text-xs text-amber-900 dark:text-amber-200 space-y-1">
                <div class="font-bold">Tips Pencetakan</div>
                <ul class="list-disc list-inside space-y-0.5 opacity-90">
                    <li>Gunakan kertas <strong>A4 art paper 230gsm</strong> atau <strong>PVC</strong> untuk hasil terbaik.</li>
                    <li>Setelah cetak, potong sesuai garis ukuran <strong>kartu kredit (85 × 54 mm)</strong>, lalu laminating.</li>
                    <li>Pastikan QR tetap kontras tinggi — jangan diberi efek atau di-resize agar bisa di-scan.</li>
                    <li>Token QR dapat di-regenerate kapan saja di menu <strong>Akademik › Siswa</strong> bila kartu hilang.</li>
                </ul>
            </div>
        </div>
    </div>
</x-filament-panels::page>
