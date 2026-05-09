<div class="bg-gradient-to-b from-emerald-50/50 via-white to-white">
    <div class="max-w-3xl mx-auto px-4 py-12 sm:py-16 space-y-6">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-slate-800">Cek Status Tiket BK</h1>
            <p class="text-slate-600 mt-2">Masukkan kode tiket untuk melihat balasan Guru BK.</p>
        </div>

        <form wire:submit="search" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col sm:flex-row gap-3">
            <input type="text" wire:model="code" placeholder="BK-XXXXXX"
                   class="flex-1 rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800 tracking-widest font-bold uppercase">
            <button type="submit"
                    style="background-color:#059669;color:#ffffff;"
                    class="px-5 py-2.5 rounded-lg text-sm font-bold">Cari</button>
        </form>

        @if($notFound)
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-700 text-sm">{{ $notFound }}</div>
        @endif

        @if($ticket)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Kode Tiket</div>
                        <div class="text-xl font-black text-emerald-700 tracking-widest">{{ $ticket->code }}</div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-0.5 rounded-md text-xs font-bold
                            @if($ticket->status === 'new') bg-blue-100 text-blue-700
                            @elseif($ticket->status === 'in_progress') bg-amber-100 text-amber-700
                            @elseif($ticket->status === 'resolved') bg-emerald-100 text-emerald-700
                            @else bg-slate-200 text-slate-700 @endif">
                            {{ $ticket->status_label }}
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold">{{ $ticket->category_label }}</span>
                    </div>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">{{ $ticket->subject }}</h2>
                    <div class="text-xs text-slate-500 mt-1">Dibuat {{ $ticket->created_at->translatedFormat('d M Y H:i') }}</div>
                </div>

                {{-- Thread --}}
                <div class="space-y-3 pt-3 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-700">Thread Pesan</h3>
                    @forelse($ticket->publicMessages as $msg)
                        @php $isCounselor = $msg->sender_type === 'counselor'; @endphp
                        <div class="flex {{ $isCounselor ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-[80%] rounded-2xl p-3 text-sm
                                {{ $isCounselor ? 'bg-emerald-50 border border-emerald-200 text-slate-800' : 'bg-slate-800 text-white' }}">
                                <div class="text-[10px] font-bold uppercase tracking-wide opacity-80 mb-1">
                                    {{ $isCounselor ? ('Guru BK' . ($msg->staffMember ? ' · '.$msg->staffMember->name : '')) : 'Anda' }}
                                    &middot; {{ $msg->created_at->translatedFormat('d M H:i') }}
                                </div>
                                <div class="whitespace-pre-wrap">{{ $msg->body }}</div>
                                @if(! empty($msg->attachments))
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach($msg->attachments as $path)
                                            <a href="{{ asset('storage/'.$path) }}" target="_blank" class="text-xs {{ $isCounselor ? 'text-emerald-700' : 'text-emerald-200' }} underline">📎 {{ basename($path) }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-sm text-slate-500 py-4">Belum ada pesan dari Guru BK.</div>
                    @endforelse
                </div>

                {{-- Reply form --}}
                @if(! in_array($ticket->status, ['resolved', 'closed']))
                    @if(session('success'))
                        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-3">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm p-3">{{ session('error') }}</div>
                    @endif
                    <form wire:submit="postReply" class="space-y-2 pt-3 border-t border-slate-100">
                        <textarea wire:model="reply" rows="3" placeholder="Tulis balasan..."
                                  class="w-full rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800"></textarea>
                        @error('reply')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
                        <div class="flex justify-end">
                            <button type="submit"
                                    style="background-color:#059669;color:#ffffff;"
                                    class="px-4 py-2 rounded-lg text-sm font-bold">Kirim Balasan</button>
                        </div>
                    </form>
                @else
                    <div class="text-xs text-slate-500 italic pt-3 border-t border-slate-100">Tiket sudah ditutup. Tidak bisa menambah balasan.</div>
                @endif
            </div>
        @endif
    </div>
</div>
