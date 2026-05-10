<div class="space-y-5 max-w-3xl mx-auto">
    <div>
        <a href="{{ route('portal.parent.leave.index', ['student' => $student->slug]) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Daftar Surat Izin
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

    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
        @if($student->photo_url)
            <img src="{{ $student->photo_url }}" class="w-12 h-12 rounded-2xl object-cover">
        @else
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold flex items-center justify-center">{{ mb_substr($student->name, 0, 1) }}</div>
        @endif
        <div class="flex-1">
            <div class="text-lg font-extrabold text-slate-800">{{ $student->name }}</div>
            <div class="text-xs text-slate-500">{{ $student->schoolClass?->name ?? '—' }}{{ $student->nis ? ' · NIS '.$student->nis : '' }}</div>
        </div>
    </div>

    <form wire:submit.prevent="submit" class="rounded-2xl border border-slate-200 bg-white p-6 space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-lg font-extrabold text-slate-800">Ajukan Surat Izin</h2>
            <p class="text-xs text-slate-500 mt-0.5">Isi formulir di bawah. Setelah disetujui sekolah, absensi siswa akan otomatis tercatat.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Izin <span class="text-rose-500">*</span></label>
                <select wire:model="type" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('type') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
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
            <textarea wire:model="reason" rows="4" placeholder="Contoh: Anak saya sakit demam dan perlu istirahat di rumah." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none"></textarea>
            @error('reason') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lampiran Bukti (opsional)</label>
            <input type="file" wire:model="attachment" accept="image/*,application/pdf" class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, WEBP, atau PDF. Maks. 2MB. Misalnya surat dokter, surat keterangan, atau foto bukti.</p>
            @error('attachment') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            <div wire:loading wire:target="attachment" class="text-xs text-indigo-600 mt-1">Mengunggah lampiran...</div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
            <a href="{{ route('portal.parent.leave.index', ['student' => $student->slug]) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-500/30 hover:scale-[1.02] transition disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading.remove wire:target="submit" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <svg wire:loading wire:target="submit" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="3" stroke-dasharray="50 100"/></svg>
                <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                <span wire:loading wire:target="submit">Mengirim...</span>
            </button>
        </div>
    </form>
</div>
