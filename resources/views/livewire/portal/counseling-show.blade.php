<div class="max-w-3xl mx-auto space-y-4">
    <a href="{{ route('portal.counseling.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-emerald-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold tracking-wider">{{ $ticket->code }}</span>
                <span class="px-2 py-0.5 rounded-md text-xs font-bold
                    @if($ticket->status === 'new') bg-blue-100 text-blue-700
                    @elseif($ticket->status === 'in_progress') bg-amber-100 text-amber-700
                    @elseif($ticket->status === 'resolved') bg-emerald-100 text-emerald-700
                    @else bg-slate-200 text-slate-700 @endif">{{ $ticket->status_label }}</span>
                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">{{ $ticket->category_label }}</span>
            </div>
            @if(! in_array($ticket->status, ['resolved', 'closed']))
                <button type="button" wire:click="closeTicket"
                        onclick="return confirm('Tutup tiket ini? Tidak bisa balas lagi setelah ditutup.');"
                        class="text-xs text-slate-500 hover:text-red-600 font-semibold">Tutup Tiket</button>
            @endif
        </div>
        <h1 class="font-bold text-slate-800 text-lg">{{ $ticket->subject }}</h1>
        <div class="text-xs text-slate-500 mt-1">Dibuat {{ $ticket->created_at->translatedFormat('d M Y H:i') }}</div>
    </div>

    {{-- Thread --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-3 min-h-[200px]">
        @forelse($messages as $msg)
            @php $isMine = $msg->sender_type === 'student'; @endphp
            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-2xl p-3 text-sm
                    {{ $isMine ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-800' }}">
                    <div class="text-[10px] font-bold uppercase tracking-wide opacity-80 mb-1">
                        @if($isMine)
                            Anda
                        @else
                            {{ $msg->sender_type === 'counselor' ? ('Guru BK'.($msg->staffMember ? ' · '.$msg->staffMember->name : '')) : 'Pelapor' }}
                        @endif
                        &middot; {{ $msg->created_at->translatedFormat('d M H:i') }}
                    </div>
                    <div class="whitespace-pre-wrap">{{ $msg->body }}</div>
                    @if(! empty($msg->attachments))
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($msg->attachments as $path)
                                <a href="{{ asset('storage/'.$path) }}" target="_blank" class="text-xs underline {{ $isMine ? 'text-emerald-100' : 'text-emerald-700' }}">📎 {{ basename($path) }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-sm text-slate-500 py-8">Belum ada pesan.</div>
        @endforelse
    </div>

    @if(session('error'))
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm p-3">{{ session('error') }}</div>
    @endif

    {{-- Reply form --}}
    @if(! in_array($ticket->status, ['resolved', 'closed']))
        <form wire:submit="postReply" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 space-y-3">
            <textarea wire:model="reply" rows="3" placeholder="Tulis balasan..."
                      class="w-full rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800"></textarea>
            @error('reply')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
            <div class="flex items-center justify-between gap-2">
                <input type="file" wire:model="files" multiple accept="image/*,.pdf,.doc,.docx"
                       class="text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-2 file:rounded file:border-0 file:bg-slate-100 file:text-slate-700 file:font-semibold">
                <button type="submit" wire:loading.attr="disabled"
                        style="background-color:#059669;color:#ffffff;"
                        class="px-4 py-2 rounded-lg text-sm font-bold disabled:opacity-60">
                    <span wire:loading.remove wire:target="postReply">Kirim</span>
                    <span wire:loading wire:target="postReply">...</span>
                </button>
            </div>
        </form>
    @else
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center text-sm text-slate-500 italic">
            Tiket sudah ditutup ({{ $ticket->status_label }}).
        </div>
    @endif
</div>
