<div class="space-y-4"
     x-data="{
        deadline: @js(optional($attempt->deadline_at)?->toIso8601String()),
        remaining: 0,
        formatted: '',
        autoSubmitted: false,
        tick() {
            if (! this.deadline) { this.formatted = '∞'; return; }
            const diff = Math.max(0, Math.floor((new Date(this.deadline) - new Date()) / 1000));
            this.remaining = diff;
            const m = Math.floor(diff / 60).toString().padStart(2, '0');
            const s = (diff % 60).toString().padStart(2, '0');
            this.formatted = m + ':' + s;
            if (diff <= 0 && ! this.autoSubmitted) {
                this.autoSubmitted = true;
                $wire.submit();
            }
        }
     }"
     x-init="tick(); setInterval(() => tick(), 1000)">

    {{-- Sticky bar --}}
    <div class="sticky top-0 z-30 -mx-4 sm:-mx-6 px-4 sm:px-6 py-3 bg-white/90 backdrop-blur border-b border-slate-200 flex items-center justify-between gap-4">
        <div class="min-w-0">
            <div class="text-xs text-slate-500 truncate">{{ $quiz->subject?->name }} &middot; Attempt #{{ $attempt->attempt_no }}</div>
            <div class="font-bold text-slate-800 truncate">{{ $quiz->title }}</div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex flex-col items-end">
                <div class="text-[10px] text-slate-500 uppercase tracking-wide">Sisa Waktu</div>
                <div class="text-lg font-extrabold tabular-nums" :class="remaining > 0 && remaining <= 60 ? 'text-red-600' : 'text-emerald-600'" x-text="formatted">--:--</div>
            </div>
            <button type="button"
                    onclick="if(confirm('Yakin ingin submit jawaban sekarang?')) { Livewire.dispatch('quiz-submit'); }"
                    wire:click="submit"
                    style="background-color:#059669;color:#ffffff;"
                    class="px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Submit</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_240px] gap-4">
        {{-- Soal area --}}
        <div class="space-y-3">
            @php $currentQ = $currentId ? $questions->get($currentId) : null; @endphp
            @if($currentQ)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-semibold text-slate-500">Soal {{ $currentIndex + 1 }} dari {{ count($orderedIds) }}</div>
                        <div class="text-xs font-bold text-slate-700">Skor: {{ $currentQ->score }}</div>
                    </div>
                    <div class="prose prose-slate max-w-none text-slate-800 text-base">
                        {!! nl2br(e($currentQ->body)) !!}
                    </div>

                    @if($currentQ->type === 'essay')
                        <textarea wire:model.live.debounce.800ms="answers.{{ $currentQ->id }}.essay"
                                  rows="8"
                                  class="w-full rounded-lg border border-slate-200 focus:border-emerald-400 focus:ring-emerald-200 text-slate-800 p-3"
                                  placeholder="Tulis jawaban Anda di sini..."></textarea>
                    @else
                        @php
                            $multi = $currentQ->type === 'multi';
                            $opts = $currentQ->options->keyBy('id');
                            $orderedOpts = $optionOrder[$currentQ->id] ?? $currentQ->options->pluck('id')->all();
                            $selected = $answers[$currentQ->id]['option_ids'] ?? [];
                        @endphp
                        <div class="space-y-2">
                            @foreach($orderedOpts as $oid)
                                @php $opt = $opts->get($oid); if (! $opt) continue; $isSel = in_array($oid, $selected, true); @endphp
                                <button type="button"
                                        wire:click="selectOption({{ $currentQ->id }}, {{ $oid }}, {{ $multi ? 'true' : 'false' }})"
                                        class="w-full text-left rounded-xl border p-3 transition flex items-start gap-3
                                            {{ $isSel ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white hover:border-emerald-300 hover:bg-emerald-50/40' }}">
                                    <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-{{ $multi ? 'md' : 'full' }} border-2 flex items-center justify-center
                                        {{ $isSel ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 text-transparent' }}">
                                        @if($multi)
                                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                        @endif
                                    </span>
                                    <span class="text-slate-800">{{ $opt->label }}</span>
                                </button>
                            @endforeach
                        </div>
                        @if($multi)
                            <div class="text-xs text-slate-500">* Anda boleh memilih lebih dari satu jawaban.</div>
                        @endif
                    @endif

                    <div class="flex items-center justify-between pt-3">
                        <button type="button" wire:click="go({{ $currentIndex - 1 }})"
                                @disabled($currentIndex === 0)
                                class="px-4 py-2 rounded-lg text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50 disabled:opacity-50">← Sebelumnya</button>
                        @if($currentIndex < count($orderedIds) - 1)
                            <button type="button" wire:click="go({{ $currentIndex + 1 }})"
                                    style="background-color:#059669;color:#ffffff;"
                                    class="px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Selanjutnya →</button>
                        @else
                            <button type="button" wire:click="submit"
                                    onclick="return confirm('Yakin ingin submit jawaban?');"
                                    style="background-color:#059669;color:#ffffff;"
                                    class="px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Submit Jawaban</button>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center text-slate-500">Tidak ada soal.</div>
            @endif
        </div>

        {{-- Sidebar daftar soal --}}
        <aside class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 h-fit lg:sticky lg:top-24">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Daftar Soal</div>
            <div class="grid grid-cols-5 lg:grid-cols-4 gap-2">
                @foreach($orderedIds as $i => $qid)
                    @php
                        $q = $questions->get($qid);
                        $a = $answers[$qid] ?? null;
                        $answered = $q && (
                            ($q->type === 'essay' && trim($a['essay'] ?? '') !== '')
                            || ($q->type !== 'essay' && ! empty($a['option_ids'] ?? []))
                        );
                        $isCurrent = $i === $currentIndex;
                    @endphp
                    <button type="button" wire:click="go({{ $i }})"
                            class="h-9 rounded-lg text-sm font-bold transition
                                {{ $isCurrent ? 'ring-2 ring-emerald-500' : '' }}
                                {{ $answered ? 'bg-emerald-100 text-emerald-700 border border-emerald-300' : 'bg-slate-50 text-slate-500 border border-slate-200 hover:bg-slate-100' }}">
                        {{ $i + 1 }}
                    </button>
                @endforeach
            </div>
            <div class="mt-4 text-xs text-slate-500 space-y-1">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></span> Sudah dijawab</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-slate-50 border border-slate-200"></span> Belum dijawab</div>
            </div>
        </aside>
    </div>
</div>
