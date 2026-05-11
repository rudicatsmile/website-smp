<div class="space-y-5 max-w-3xl">
    <div class="flex items-center gap-3">
        <a href="{{ route('portal.parent.notes.index', ['student' => $student->slug]) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-600 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Buku Penghubung
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 flex items-center gap-4">
        @if($student->photo_url)
            <img src="{{ $student->photo_url }}" class="w-12 h-12 rounded-2xl object-cover">
        @else
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-bold flex items-center justify-center">{{ mb_substr($student->name, 0, 1) }}</div>
        @endif
        <div class="flex-1">
            <div class="text-lg font-extrabold text-slate-800">{{ $student->name }}</div>
            <div class="text-xs text-slate-500">Wali kelas: {{ $student->schoolClass?->homeroomTeacher?->name ?? 'Belum ditentukan' }}</div>
        </div>
    </div>

    <form wire:submit="submit" class="rounded-2xl border border-slate-200 bg-white p-6 space-y-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Subjek <span class="text-rose-500">*</span></label>
            <input type="text" wire:model="subject" maxlength="200" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Mis. Perkembangan belajar Mei 2026">
            @error('subject')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                <select wire:model="category" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Prioritas</label>
                <select wire:model="priority" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    @foreach($priorities as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Pesan <span class="text-rose-500">*</span></label>
            <textarea wire:model="body" rows="6" maxlength="5000" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Tulis pertanyaan, laporan, atau konsultasi Anda kepada wali kelas..."></textarea>
            @error('body')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Lampiran (opsional)</label>
            <input type="file" wire:model="files" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-semibold hover:file:bg-emerald-100">
            <div class="text-xs text-slate-500 mt-1">Maks 3 file, 5MB per file. Format: jpg, png, pdf, doc.</div>
            @error('files.*')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
            <div wire:loading wire:target="files" class="text-xs text-emerald-600 mt-1">Mengupload...</div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:scale-[1.02] transition disabled:opacity-60 disabled:cursor-wait" wire:loading.attr="disabled">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span wire:loading.remove wire:target="submit">Kirim Topik</span>
                <span wire:loading wire:target="submit">Mengirim...</span>
            </button>
            <a href="{{ route('portal.parent.notes.index', ['student' => $student->slug]) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">Batal</a>
        </div>
    </form>
</div>
