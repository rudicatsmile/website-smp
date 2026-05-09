<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('portal.counseling.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Buat Pengaduan Konseling</h1>
        <p class="text-sm text-slate-500">Ceritakan dengan tenang. Pesanmu hanya dibaca Guru BK.</p>
    </div>

    <form wire:submit="submit" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select wire:model="category" class="w-full rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800">
                    <option value="">— Pilih kategori —</option>
                    @foreach($categories as $k => $label)
                        <option value="{{ $k }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tingkat Urgensi</label>
                <select wire:model="priority" class="w-full rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800">
                    @foreach($priorities as $k => $label)
                        <option value="{{ $k }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Subjek <span class="text-red-500">*</span></label>
            <input type="text" wire:model="subject" maxlength="120"
                   class="w-full rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800">
            @error('subject')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Uraian <span class="text-red-500">*</span></label>
            <textarea wire:model="body" rows="6" maxlength="5000"
                      class="w-full rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800"></textarea>
            @error('body')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Lampiran (opsional)</label>
            <input type="file" wire:model="files" multiple accept="image/*,.pdf,.doc,.docx"
                   class="w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-semibold hover:file:bg-emerald-100">
            @error('files.*')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-xs text-emerald-900">
            🔒 Data ini hanya dibaca oleh Guru BK. Kamu bisa melanjutkan percakapan di thread tiket.
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    wire:loading.attr="disabled"
                    style="background-color:#059669;color:#ffffff;"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm disabled:opacity-60">
                <span wire:loading.remove wire:target="submit">Kirim</span>
                <span wire:loading wire:target="submit">Mengirim...</span>
            </button>
        </div>
    </form>
</div>
