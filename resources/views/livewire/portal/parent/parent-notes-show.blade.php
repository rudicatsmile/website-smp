<div class="space-y-5 max-w-4xl">
    @if(session('error'))
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-3 text-rose-800 text-sm">{{ session('error') }}</div>
    @endif

    <div class="flex items-center gap-3">
        <a href="{{ route('portal.parent.notes.index', ['student' => $note->student->slug]) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-600 font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Daftar Topik
        </a>
    </div>

    @php
        $statusColors = [
            'open'     => 'bg-blue-100 text-blue-800',
            'replied'  => 'bg-amber-100 text-amber-800',
            'resolved' => 'bg-emerald-100 text-emerald-800',
            'closed'   => 'bg-slate-200 text-slate-700',
        ];
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex items-start justify-between gap-3 flex-wrap">
            <div class="flex-1 min-w-0">
                <div class="text-xs text-slate-500 mb-1">Kode {{ $note->code }} &middot; {{ $note->category_label }}</div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800">{{ $note->subject }}</h1>
                <div class="text-sm text-slate-600 mt-1">
                    <strong>{{ $note->student->name }}</strong> &middot; {{ $note->schoolClass?->name ?? '—' }} &middot; Wali Kelas: {{ $note->homeroomTeacher?->name ?? '—' }}
                </div>
            </div>
            <span class="text-xs px-3 py-1 rounded-full font-bold {{ $statusColors[$note->status] ?? 'bg-slate-100 text-slate-700' }}">{{ $note->status_label }}</span>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 space-y-4">
        <div class="text-sm font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Percakapan ({{ $messages->count() }})
        </div>

        <div class="space-y-3">
            @foreach($messages as $msg)
                @php $isParent = $msg->sender_type === 'parent'; @endphp
                <div class="flex {{ $isParent ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] rounded-2xl px-4 py-3 {{ $isParent ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-800' }}">
                        <div class="text-[11px] font-semibold mb-1 {{ $isParent ? 'text-emerald-100' : 'text-slate-500' }}">
                            {{ $isParent ? ($msg->user?->name ?? 'Anda') : ('Wali Kelas: ' . ($msg->staffMember?->name ?? $msg->user?->name ?? '—')) }}
                            &middot;
                            {{ $msg->created_at->translatedFormat('d M Y H:i') }}
                        </div>
                        <div class="whitespace-pre-wrap text-sm leading-relaxed">{{ $msg->body }}</div>

                        @if(!empty($msg->attachments))
                            <div class="mt-2 space-y-1">
                                @foreach($msg->attachments as $path)
                                    <a href="{{ asset('storage/' . $path) }}" target="_blank" class="block text-xs underline {{ $isParent ? 'text-emerald-50' : 'text-emerald-700' }}">
                                        📎 {{ basename($path) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if(in_array($note->status, ['resolved', 'closed']))
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-center text-sm text-slate-600">
                Topik ini sudah <strong>{{ $note->status_label }}</strong>. Buat topik baru jika ada hal lain yang ingin didiskusikan.
            </div>
        @else
            <form wire:submit="postReply" class="border-t border-slate-100 pt-4 space-y-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tulis balasan</label>
                    <textarea wire:model="reply" rows="3" maxlength="3000" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Tulis balasan untuk wali kelas..."></textarea>
                    @error('reply')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Lampiran (opsional, maks 3 file 5MB)</label>
                    <input type="file" wire:model="files" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-semibold hover:file:bg-emerald-100">
                    @error('files.*')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
                    <div wire:loading wire:target="files" class="text-xs text-emerald-600 mt-1">Mengupload...</div>
                </div>

                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:scale-[1.02] transition disabled:opacity-60 disabled:cursor-wait" wire:loading.attr="disabled">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <span wire:loading.remove wire:target="postReply">Kirim Balasan</span>
                        <span wire:loading wire:target="postReply">Mengirim...</span>
                    </button>

                    <button type="button" wire:click="closeNote" wire:confirm="Tutup topik ini? Anda tidak bisa membalas lagi setelahnya." class="text-sm font-semibold text-slate-500 hover:text-rose-600">
                        Tandai Selesai
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
