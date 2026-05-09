<div>
    {{-- HERO BACKGROUND --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-teal-50/40 to-white">
        {{-- decorative blobs --}}
        <div aria-hidden="true" class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 rounded-full opacity-50 blur-3xl" style="background:radial-gradient(closest-side,#a7f3d0,transparent);"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-32 -right-24 w-[28rem] h-[28rem] rounded-full opacity-40 blur-3xl" style="background:radial-gradient(closest-side,#5eead4,transparent);"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-16">

            @if($submittedTicket)
                {{-- ============== SUCCESS CARD ============== --}}
                <div x-data="{ copied:false, copy(){ navigator.clipboard.writeText('{{ $submittedTicket->code }}'); this.copied = true; setTimeout(() => this.copied=false, 2000); } }"
                     class="max-w-2xl mx-auto bg-white rounded-3xl border border-emerald-100 shadow-2xl shadow-emerald-100/50 p-8 sm:p-12 text-center space-y-6 relative overflow-hidden">
                    <div aria-hidden="true" class="absolute inset-x-0 top-0 h-1.5" style="background:linear-gradient(90deg,#10b981,#0d9488,#0891b2);"></div>

                    <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center" style="background:linear-gradient(135deg,#d1fae5,#ccfbf1);">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:linear-gradient(135deg,#10b981,#0d9488);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Terima kasih sudah bercerita 💚</h1>
                        <p class="text-slate-600 mt-2 text-sm sm:text-base max-w-md mx-auto">Pengaduanmu sudah kami terima dengan aman. Guru BK akan segera membaca dan menanggapi.</p>
                    </div>

                    <div class="rounded-2xl border-2 border-dashed border-emerald-300 bg-gradient-to-br from-emerald-50 to-teal-50 p-5">
                        <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-widest">Kode Tiketmu</div>
                        <div class="flex items-center justify-center gap-3 mt-2">
                            <div class="text-3xl sm:text-4xl font-black text-emerald-700 tracking-[0.2em] font-mono">{{ $submittedTicket->code }}</div>
                            <button type="button" @click="copy()" class="p-2 rounded-lg hover:bg-white transition" :class="copied ? 'text-emerald-600' : 'text-slate-500'" title="Salin kode">
                                <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg x-show="copied" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                        <div class="text-xs text-slate-600 mt-2" x-text="copied ? '✓ Tersalin ke clipboard' : 'Klik ikon untuk menyalin'"></div>
                    </div>

                    <div class="rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-900 text-left flex gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span><strong>Penting:</strong> Simpan kode ini baik-baik. Tanpa kode, kamu tidak bisa membuka kembali balasan dari Guru BK.</span>
                    </div>

                    <div class="flex flex-wrap justify-center gap-2 pt-2">
                        <a href="{{ route('bk.status', ['kode' => $submittedTicket->code]) }}"
                           style="background:linear-gradient(135deg,#059669,#0d9488);color:#ffffff;"
                           class="px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-emerald-200 hover:shadow-lg transition flex items-center gap-2">
                            Cek Status Tiket
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('bk.form') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition">Kirim Pengaduan Lain</a>
                    </div>
                </div>

            @else
                {{-- ============== HEADER ============== --}}
                <div class="text-center mb-10 sm:mb-12">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-emerald-200 text-emerald-700 text-xs font-bold shadow-sm mb-5">
                        <span class="relative flex w-2 h-2">
                            <span class="animate-ping absolute inline-flex w-full h-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full w-2 h-2 bg-emerald-500"></span>
                        </span>
                        Layanan Konseling Aktif &middot; Respons rata-rata < 24 jam
                    </div>
                    <h1 class="text-3xl sm:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                        Ruang Aman untuk
                        <span class="bg-clip-text text-transparent" style="background-image:linear-gradient(135deg,#059669,#0d9488,#0891b2);">Bercerita</span>
                    </h1>
                    <p class="text-slate-600 mt-4 max-w-2xl mx-auto text-base sm:text-lg">
                        Ceritakan pelan-pelan apa yang sedang kamu rasakan. Tim Guru BK siap mendengar dan menjaga kerahasiaanmu sepenuhnya.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 lg:gap-8 items-start">

                    {{-- ============== FORM ============== --}}
                    <form wire:submit="submit"
                          x-data="{ get bodyLen() { return ($wire.body || '').length; }, get anonymous() { return ! ($wire.reporter_name || '').trim(); } }"
                          class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">

                        {{-- form header --}}
                        <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-white to-emerald-50/40">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#10b981,#0d9488);">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-800 text-base sm:text-lg">Form Pengaduan / Konsultasi</div>
                                    <div class="text-xs text-slate-500">Semua field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8 space-y-6">

                            {{-- 1. CATEGORY CHIPS --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold mr-2">1</span>
                                    Kategori Pengaduan <span class="text-red-500">*</span>
                                </label>
                                @php
                                    $catIcons = [
                                        'akademik' => '📚',
                                        'pribadi' => '🌱',
                                        'keluarga' => '🏠',
                                        'pertemanan' => '🤝',
                                        'kesehatan' => '💚',
                                        'ekonomi' => '💰',
                                        'lainnya' => '✨',
                                    ];
                                @endphp
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                                    @foreach($categories as $k => $label)
                                        @php $isActive = $category === $k; @endphp
                                        <button type="button" wire:click="$set('category', '{{ $k }}')"
                                                class="group relative flex flex-col items-center gap-1.5 px-3 py-3 rounded-xl border-2 transition-all duration-150 text-center
                                                    {{ $isActive ? 'border-emerald-500 bg-emerald-50 shadow-md shadow-emerald-100' : 'border-slate-200 bg-white hover:border-emerald-300 hover:bg-emerald-50/40' }}">
                                            <span class="text-2xl">{{ $catIcons[$k] ?? '•' }}</span>
                                            <span class="text-xs font-bold {{ $isActive ? 'text-emerald-700' : 'text-slate-700' }} leading-tight">{{ $label }}</span>
                                            @if($isActive)
                                                <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center">
                                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                                @error('category')<div class="text-red-600 text-xs mt-2 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>

                            {{-- 2. SUBJECT --}}
                            <div>
                                <label for="subject" class="block text-sm font-bold text-slate-700 mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold mr-2">2</span>
                                    Subjek / Judul Singkat <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </span>
                                    <input id="subject" type="text" wire:model="subject" maxlength="120"
                                           placeholder="Contoh: Kesulitan belajar matematika"
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-solid border-slate-400 bg-white outline outline-2 outline-offset-0 outline-slate-300 focus:outline-emerald-500 focus:outline-offset-2 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100 text-slate-800 transition">
                                </div>
                                @error('subject')<div class="text-red-600 text-xs mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>

                            {{-- 3. BODY --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="body" class="block text-sm font-bold text-slate-700">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold mr-2">3</span>
                                        Ceritakan yang Kamu Rasakan <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[11px] font-semibold tabular-nums" :class="bodyLen >= 20 ? 'text-emerald-600' : 'text-slate-400'">
                                        <span x-text="bodyLen">{{ strlen($body) }}</span>/5000
                                    </span>
                                </div>
                                <textarea id="body" wire:model.live.debounce.500ms="body" rows="7" maxlength="5000"
                                          placeholder="Tulis bebas. Minimal 20 karakter. Tidak ada salah atau benar — apa pun yang kamu rasakan, sah untuk diceritakan di sini..."
                                          class="w-full px-4 py-3 rounded-xl border-2 border-solid border-slate-400 bg-white outline outline-2 outline-offset-0 outline-slate-300 focus:outline-emerald-500 focus:outline-offset-2 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100 text-slate-800 transition leading-relaxed"></textarea>
                                <div class="text-xs text-slate-500 mt-1.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Pesanmu dienkripsi dan hanya bisa diakses Guru BK.
                                </div>
                                @error('body')<div class="text-red-600 text-xs mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>

                            {{-- 4. IDENTITAS --}}
                            <div class="rounded-2xl border-2 border-slate-100 p-4 sm:p-5 bg-slate-50/40">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div>
                                        <div class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold">4</span>
                                            Identitas (Opsional)
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5">Kosongkan jika ingin tetap anonim — kami menghormatinya.</div>
                                    </div>
                                    <span x-show="anonymous" class="px-2.5 py-1 rounded-full bg-slate-800 text-white text-[10px] font-bold tracking-wide flex-shrink-0 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        ANONIM
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama</label>
                                        <div>
                                            <input type="text" wire:model.live="reporter_name" maxlength="100"
                                                   class="w-full px-3 py-2.5 rounded-lg border-2 border-solid border-slate-400 bg-white outline outline-2 outline-offset-0 outline-slate-300 focus:outline-emerald-500 focus:outline-offset-2 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100 text-sm text-slate-800 transition">
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1.5">Kosongkan untuk anonim.</div>
                                        @error('reporter_name')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kontak (Email / WhatsApp)</label>
                                        <div>
                                            <input type="text" wire:model="reporter_contact" maxlength="150"
                                                   class="w-full px-3 py-2.5 rounded-lg border-2 border-solid border-slate-400 bg-white outline outline-2 outline-offset-0 outline-slate-300 focus:outline-emerald-500 focus:outline-offset-2 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100 text-sm text-slate-800 transition">
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1.5">Agar BK bisa menghubungi.</div>
                                        @error('reporter_contact')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- 5. LAMPIRAN --}}
                            <div>
                                <label for="files" class="block text-sm font-bold text-slate-700 mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold mr-2">5</span>
                                    Lampiran <span class="text-slate-400 font-normal">(Opsional)</span>
                                </label>
                                <label for="files" class="flex flex-col items-center justify-center gap-2 px-4 py-6 rounded-2xl border-2 border-dashed border-slate-400 bg-slate-50/40 hover:bg-emerald-50/40 hover:border-emerald-500 transition cursor-pointer text-center">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <div class="text-sm font-semibold text-slate-700">Klik untuk pilih file</div>
                                    <div class="text-xs text-slate-500">Maks 3 file × 5MB &middot; JPG, PNG, PDF, DOC</div>
                                </label>
                                <input id="files" type="file" wire:model="files" multiple accept="image/*,.pdf,.doc,.docx" class="hidden">
                                @if(! empty($files))
                                    <div class="mt-3 space-y-1.5">
                                        @foreach($files as $f)
                                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-xs">
                                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                                <span class="font-semibold text-emerald-900 truncate">{{ method_exists($f, 'getClientOriginalName') ? $f->getClientOriginalName() : 'File terpilih' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @error('files.*')<div class="text-red-600 text-xs mt-1.5">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="px-6 sm:px-8 py-5 border-t border-slate-100 bg-slate-50/40 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <a href="{{ route('bk.status') }}" class="text-sm text-slate-600 hover:text-emerald-700 font-semibold inline-flex items-center gap-1 order-2 sm:order-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Sudah punya kode tiket? Cek status
                            </a>
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    style="background:linear-gradient(135deg,#059669,#0d9488);color:#ffffff;"
                                    class="w-full sm:w-auto order-1 sm:order-2 px-6 py-3 rounded-xl text-sm font-extrabold shadow-lg shadow-emerald-200 hover:shadow-xl hover:shadow-emerald-300/50 hover:-translate-y-0.5 transition disabled:opacity-60 disabled:cursor-wait inline-flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="submit" class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Kirim Pengaduan
                                </span>
                                <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Mengirim...
                                </span>
                            </button>
                        </div>
                    </form>

                    {{-- ============== SIDEBAR ============== --}}
                    <aside class="space-y-4 lg:sticky lg:top-24">

                        {{-- Trust card --}}
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-md p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-emerald-100 text-emerald-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <div class="font-extrabold text-slate-800">Kerahasiaanmu Aman</div>
                            </div>
                            <ul class="space-y-2 text-sm text-slate-700">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Identitas opsional — bisa anonim total.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Hanya Guru BK yang bisa membaca.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Tidak ada penilaian — semua perasaan valid.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Bisa tindak lanjut via kode tiket.</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Steps card --}}
                        <div class="rounded-2xl p-5 text-white shadow-md" style="background:linear-gradient(135deg,#0d9488 0%,#059669 100%);">
                            <div class="font-extrabold text-base mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Bagaimana Prosesnya?
                            </div>
                            <ol class="space-y-3 text-sm">
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-xs font-extrabold">1</span>
                                    <span class="opacity-95">Isi formulir.</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-xs font-extrabold">2</span>
                                    <span class="opacity-95">Dapatkan kode tiket unik.</span>
                                </li>
                                <li class="flex gap-3">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-xs font-extrabold">3</span>
                                    <span class="opacity-95">Cek balasan kapan saja di halaman <strong class="underline">Cek Status</strong>.</span>
                                </li>
                            </ol>
                        </div>

                        {{-- Hotline card --}}
                        <div class="bg-white rounded-2xl border border-rose-100 shadow-md p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-rose-100 text-rose-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div class="font-extrabold text-slate-800">Butuh Bantuan Segera?</div>
                            </div>
                            <p class="text-xs text-slate-600 mb-3">Jika kamu sedang dalam krisis atau mengalami pikiran menyakiti diri sendiri, hubungi:</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg bg-rose-50 border border-rose-100">
                                    <div>
                                        <div class="text-xs text-slate-500">Hotline Kemenkes</div>
                                        <div class="font-extrabold text-rose-700">119 ext 8</div>
                                    </div>
                                    <a href="tel:119" class="text-xs font-bold text-rose-700 underline">Telepon</a>
                                </div>
                                <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg bg-rose-50 border border-rose-100">
                                    <div>
                                        <div class="text-xs text-slate-500">Into The Light ID</div>
                                        <div class="font-extrabold text-rose-700">@intothelightID</div>
                                    </div>
                                    <a href="https://www.intothelightid.org" target="_blank" rel="noopener" class="text-xs font-bold text-rose-700 underline">Buka</a>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            @endif
        </div>
    </div>
</div>
