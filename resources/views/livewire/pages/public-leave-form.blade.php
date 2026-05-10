<div class="bg-gradient-to-b from-indigo-50/50 via-white to-white">
    <div class="max-w-3xl mx-auto px-4 py-12 sm:py-16 space-y-6">
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-800">Surat Izin Online</h1>
            <p class="text-slate-600 mt-2">Ajukan izin tidak masuk sekolah secara online. Pengajuan akan ditinjau oleh sekolah.</p>
            <a href="{{ route('izin.status') }}" class="inline-flex items-center gap-1 mt-3 text-sm font-semibold text-indigo-600 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Cek Status Pengajuan
            </a>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-700 text-sm">
                <div class="font-bold mb-1">Mohon perbaiki kesalahan berikut:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIS Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="nis" placeholder="Nomor Induk Siswa"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    @error('nis') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Orang Tua <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="parent_name" placeholder="Nama lengkap orang tua/wali"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    @error('parent_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. WhatsApp Orang Tua</label>
                    <input type="text" wire:model="parent_phone" placeholder="08xxxxxxxxxx (opsional)"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    <p class="text-xs text-slate-500 mt-1">Opsional. Untuk verifikasi tambahan jika diperlukan.</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Izin <span class="text-rose-500">*</span></label>
                    <select wire:model="type" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dari Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="date_from" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    @error('date_from') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sampai Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="date_to" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    @error('date_to') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alasan / Keterangan <span class="text-rose-500">*</span></label>
                <textarea wire:model="reason" rows="4" placeholder="Contoh: Anak saya sakit demam dan perlu istirahat di rumah."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none"></textarea>
                @error('reason') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lampiran Bukti (opsional)</label>
                <input type="file" wire:model="attachment" accept="image/*,application/pdf"
                    class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-slate-500 mt-1">JPG, PNG, WEBP, atau PDF. Maks. 2MB. Misalnya surat dokter.</p>
                @error('attachment') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                <div wire:loading wire:target="attachment" class="text-xs text-indigo-600 mt-1">Mengunggah...</div>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="submit" wire:loading.attr="disabled"
                    style="background-color:#4f46e5;color:#ffffff;"
                    class="inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-bold shadow-lg shadow-indigo-500/30 hover:scale-[1.02] transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                    <span wire:loading wire:target="submit">Mengirim...</span>
                </button>
            </div>
        </form>
    </div>
</div>
