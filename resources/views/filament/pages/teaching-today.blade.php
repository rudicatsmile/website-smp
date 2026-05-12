<x-filament-panels::page>
@php
    $stats = $this->stats;
    $teacherName = auth()->user()?->staffMember?->name ?? auth()->user()?->name;
    $total = $stats['total'];
    $completed = $stats['completed'];
    $ongoing = $stats['ongoing'];
    $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
    $hijri = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
@endphp

{{-- HERO HEADER --}}
<div style="position:relative; overflow:hidden; border-radius:16px; padding:24px 28px; color:white; background:linear-gradient(135deg, #4f46e5 0%, #6366f1 40%, #8b5cf6 100%); box-shadow:0 10px 25px -5px rgba(79,70,229,0.35), 0 8px 10px -6px rgba(0,0,0,0.1);">
    <div style="position:absolute; right:-48px; top:-48px; width:224px; height:224px; border-radius:9999px; background:rgba(255,255,255,0.1); filter:blur(48px);"></div>
    <div style="position:absolute; left:-32px; bottom:-32px; width:192px; height:192px; border-radius:9999px; background:rgba(167,139,250,0.25); filter:blur(48px);"></div>
    <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(255,255,255,0.05), transparent);"></div>

    <div style="position:relative; display:flex; align-items:center; justify-content:space-between; gap:1.5rem; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:1rem; flex:1; min-width:280px;">
            <div style="width:64px; height:64px; border-radius:16px; background:rgba(255,255,255,0.2); backdrop-filter:blur(12px); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); flex-shrink:0; border:2px solid rgba(255,255,255,0.3);">
                <svg style="width:30px;height:30px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <div style="display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:9999px; background:rgba(255,255,255,0.15); font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;">
                    <span style="width:6px;height:6px;border-radius:9999px;background:#c4b5fd;display:inline-block;"></span>
                    Akademik · Mengajar
                </div>
                <h2 style="font-size:26px; font-weight:800; letter-spacing:-0.025em; line-height:1.15; margin-top:6px;">Mengajar Hari Ini</h2>
                <p style="font-size:13px; opacity:0.92; margin-top:4px; text-transform:capitalize;">{{ $hijri }} · {{ $teacherName }}</p>
            </div>
        </div>

        {{-- Progress ring --}}
        <div style="display:flex; align-items:center; gap:1rem;">
            <div style="text-align:right;">
                <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.1em; opacity:0.85; font-weight:700;">Sesi Selesai</div>
                <div style="font-size:30px; font-weight:800; line-height:1.1; margin-top:2px;">{{ $completed }}<span style="font-size:15px; opacity:0.8;">/{{ $total }}</span></div>
                <div style="font-size:11px; opacity:0.9;">{{ $progress }}% tuntas</div>
            </div>
            <div style="position:relative; width:80px; height:80px; flex-shrink:0;">
                @php
                    $circ = 2 * 3.1416 * 32;
                    $offset = $circ - ($circ * $progress / 100);
                @endphp
                <svg style="width:80px;height:80px;transform:rotate(-90deg);filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));" viewBox="0 0 72 72">
                    <circle cx="36" cy="36" r="32" stroke="rgba(255,255,255,0.25)" stroke-width="6" fill="none"/>
                    <circle cx="36" cy="36" r="32" stroke="white" stroke-width="6" fill="none"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $circ }}"
                            stroke-dashoffset="{{ $offset }}"/>
                </svg>
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:17px; font-weight:800;">{{ $progress }}%</div>
            </div>
        </div>
    </div>
</div>

{{-- STATS PILLS --}}
@php
    $cards = [
        ['key' => 'published', 'label' => 'Belum Mulai', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'gradient' => 'linear-gradient(135deg,#3b82f6,#0284c7)', 'text' => 'text-blue-600'],
        ['key' => 'ongoing', 'label' => 'Berlangsung', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z', 'gradient' => 'linear-gradient(135deg,#f97316,#d97706)', 'text' => 'text-orange-600'],
        ['key' => 'completed', 'label' => 'Selesai', 'icon' => 'M5 13l4 4L19 7', 'gradient' => 'linear-gradient(135deg,#10b981,#16a34a)', 'text' => 'text-emerald-600'],
        ['key' => 'cancelled', 'label' => 'Dibatalkan', 'icon' => 'M6 18L18 6M6 6l12 12', 'gradient' => 'linear-gradient(135deg,#f43f5e,#dc2626)', 'text' => 'text-rose-600'],
    ];
@endphp
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem;">
    @foreach($cards as $c)
        <div style="position:relative; overflow:hidden; border-radius:12px; border:1px solid rgba(229,231,235,0.6); padding:14px; box-shadow:0 1px 2px 0 rgba(0,0,0,0.05); transition:all 0.2s;" class="bg-white dark:bg-white/5 dark:border-white/10 hover:shadow-md">
            <div style="position:absolute; left:0; top:0; bottom:0; width:4px; background:{{ $c['gradient'] }};"></div>
            <div style="position:absolute; right:-16px; top:-16px; width:64px; height:64px; border-radius:9999px; background:{{ $c['gradient'] }}; opacity:0.12; filter:blur(8px);"></div>
            <div style="position:relative; display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <div style="width:32px; height:32px; border-radius:9px; background:{{ $c['gradient'] }}; display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/></svg>
                </div>
                <div style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;" class="text-gray-500 dark:text-gray-400">{{ $c['label'] }}</div>
            </div>
            <div style="position:relative; display:flex; align-items:baseline; gap:4px;">
                <div class="{{ $c['text'] }} dark:text-white" style="font-size:28px; font-weight:800; line-height:1;">{{ $stats[$c['key']] ?? 0 }}</div>
                @if($total > 0)
                    <div style="font-size:11px; font-weight:600;" class="text-gray-400 dark:text-gray-500">/ {{ $total }}</div>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{-- SESSION LIST --}}
@if($sessions->isEmpty())
    <div class="rounded-2xl border border-gray-200/60 dark:border-white/10 bg-white/90 dark:bg-white/5 backdrop-blur-sm p-10 shadow-md text-center">
        <div style="width:64px; height:64px; border-radius:16px; background:linear-gradient(135deg, #e0e7ff, #c7d2fe); display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
            <svg style="width:28px;height:28px;color:#6366f1;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div style="font-size:16px; font-weight:700; color:#374151;">Tidak ada sesi mengajar hari ini</div>
        <div style="font-size:13px; color:#9ca3af; margin-top:4px;">Silakan cek jadwal di menu Sesi Mengajar</div>
    </div>
@else
    <div style="display:flex; flex-direction:column; gap:0.75rem;">
        @foreach($sessions as $session)
            @php
                $isSelected = $selectedSessionId === $session->id;
                $statusGradients = [
                    'draft' => 'linear-gradient(135deg,#94a3b8,#64748b)',
                    'published' => 'linear-gradient(135deg,#3b82f6,#2563eb)',
                    'ongoing' => 'linear-gradient(135deg,#f97316,#ea580c)',
                    'completed' => 'linear-gradient(135deg,#10b981,#059669)',
                    'cancelled' => 'linear-gradient(135deg,#f43f5e,#e11d48)',
                ];
                $statusBg = [
                    'draft' => 'rgba(148,163,184,0.1)',
                    'published' => 'rgba(59,130,246,0.1)',
                    'ongoing' => 'rgba(249,115,22,0.1)',
                    'completed' => 'rgba(16,185,129,0.1)',
                    'cancelled' => 'rgba(244,63,94,0.1)',
                ];
                $statusText = [
                    'draft' => '#475569',
                    'published' => '#1d4ed8',
                    'ongoing' => '#c2410c',
                    'completed' => '#047857',
                    'cancelled' => '#be123c',
                ];
            @endphp

            <div style="position:relative; overflow:hidden; border-radius:14px; border:1px solid rgba(229,231,235,0.6); padding:20px; box-shadow:0 1px 3px 0 rgba(0,0,0,0.05); transition:all 0.2s;{{ $isSelected ? ' border-color:#6366f1; box-shadow:0 0 0 2px rgba(99,102,241,0.3);' : '' }}" class="bg-white dark:bg-white/5 dark:border-white/10">
                {{-- Left accent bar --}}
                <div style="position:absolute; left:0; top:0; bottom:0; width:4px; background:{{ $statusGradients[$session->status] ?? $statusGradients['draft'] }};"></div>

                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                    <div style="flex:1; min-width:0;">
                        {{-- Time + Status row --}}
                        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:6px;">
                            <div style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:9999px; font-size:11px; font-weight:700; background:{{ $statusBg[$session->status] ?? 'rgba(148,163,184,0.1)' }}; color:{{ $statusText[$session->status] ?? '#475569' }};">
                                <span style="width:6px;height:6px;border-radius:9999px;background:currentColor;display:inline-block;"></span>
                                {{ $session->status_label }}
                            </span>
                            <span style="font-size:13px; font-weight:700; color:#374151;">{{ $session->time_range }}</span>
                            @if($session->period)
                                <span style="font-size:11px; color:#9ca3af;">· {{ $session->period }}</span>
                            @endif
                        </div>

                        {{-- Topic --}}
                        <h3 style="font-size:16px; font-weight:800; color:#111827; line-height:1.3; margin-bottom:4px;">{{ $session->topic }}</h3>

                        {{-- Meta --}}
                        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; font-size:12px; color:#6b7280; margin-bottom:6px;">
                            <span style="font-weight:700; color:#6366f1;">{{ $session->subject?->name }}</span>
                            <span>·</span>
                            <span>Kelas {{ $session->schoolClass?->name }}</span>
                        </div>

                        @if($session->learning_objectives)
                            <p style="font-size:12px; color:#9ca3af; line-height:1.5; margin-bottom:4px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $session->learning_objectives }}</p>
                        @endif

                        {{-- Completed summary --}}
                        @if($session->status === 'completed')
                            <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; margin-top:8px; padding:8px 12px; border-radius:10px; background:rgba(16,185,129,0.08); font-size:12px; color:#047857; font-weight:600;">
                                <span>Pencapaian: <strong>{{ $session->achievement_percent ?? '—' }}%</strong></span>
                                @if($session->homework_notes)
                                    <span>·</span>
                                    <span>PR: {{ \Illuminate\Support\Str::limit($session->homework_notes, 50) }}</span>
                                @endif
                            </div>
                        @endif

                        {{-- Cancelled reason --}}
                        @if($session->status === 'cancelled')
                            <div style="margin-top:8px; padding:8px 12px; border-radius:10px; background:rgba(244,63,94,0.08); font-size:12px; color:#be123c; font-weight:600;">
                                Dibatalkan: {{ $session->cancelled_reason }}
                            </div>
                        @endif
                    </div>

                    {{-- Action buttons --}}
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; flex-shrink:0;">
                        @if($session->status === 'published')
                            <button type="button" wire:click="startSession({{ $session->id }})"
                                    style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:10px; font-size:12px; font-weight:700; color:white; background:linear-gradient(135deg,#f97316,#ea580c); border:none; cursor:pointer; box-shadow:0 4px 10px -2px rgba(249,115,22,0.4); transition:all 0.2s;"
                                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 14px -2px rgba(249,115,22,0.5)';"
                                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 10px -2px rgba(249,115,22,0.4)';">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                Mulai
                            </button>
                        @endif

                        @if($session->status === 'ongoing')
                            <button type="button" wire:click="selectSession({{ $session->id }})"
                                    style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:10px; font-size:12px; font-weight:700; color:white; background:linear-gradient(135deg,#10b981,#059669); border:none; cursor:pointer; box-shadow:0 4px 10px -2px rgba(16,185,129,0.4); transition:all 0.2s;"
                                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 14px -2px rgba(16,185,129,0.5)';"
                                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 10px -2px rgba(16,185,129,0.4)';">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Selesai
                            </button>
                        @endif

                        @if(in_array($session->status, ['published', 'ongoing']))
                            <button type="button"
                                    wire:click="cancelSession({{ $session->id }}, 'Dibatalkan oleh guru')"
                                    wire:confirm="Batalkan sesi ini?"
                                    style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:10px; font-size:12px; font-weight:700; color:#dc2626; background:rgba(220,38,38,0.08); border:1px solid rgba(220,38,38,0.2); cursor:pointer; transition:all 0.2s;"
                                    onmouseover="this.style.background='rgba(220,38,38,0.15)';"
                                    onmouseout="this.style.background='rgba(220,38,38,0.08)';">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Batal
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Materials --}}
                @if($session->materials->isNotEmpty())
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-top:12px; padding-top:12px; border-top:1px solid rgba(229,231,235,0.4);">
                        <span style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#9ca3af;">Materi</span>
                        @foreach($session->materials as $mat)
                            <a href="{{ $mat->file_url }}" target="_blank"
                               style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:8px; font-size:11px; font-weight:600; color:#6366f1; background:rgba(99,102,241,0.08); text-decoration:none; transition:all 0.15s;"
                               onmouseover="this.style.background='rgba(99,102,241,0.15)';"
                               onmouseout="this.style.background='rgba(99,102,241,0.08)';">
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                {{ \Illuminate\Support\Str::limit($mat->title, 25) }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Completion form --}}
                @if($isSelected && $session->status === 'ongoing')
                    <div style="margin-top:14px; padding-top:14px; border-top:1px solid rgba(229,231,235,0.6);">
                        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:12px;">
                            <div style="width:24px; height:24px; border-radius:7px; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; color:white;">
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#374151;">Realisasi Pembelajaran</div>
                        </div>
                        <form wire:submit.prevent="
                            completeSession({{ $session->id }}, {
                                achievement_percent: $event.target.achievement_percent.value,
                                execution_notes: $event.target.execution_notes.value,
                                homework_notes: $event.target.homework_notes.value,
                                issues_notes: $event.target.issues_notes.value
                            })"
                            style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:0.75rem;">
                            <div>
                                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:4px; color:#6b7280;">Pencapaian (%)</label>
                                <input name="achievement_percent" type="number" min="0" max="100" placeholder="0-100"
                                       style="width:100%; padding:8px 12px; border-radius:9px; font-size:13px; font-weight:600; border:1px solid rgba(229,231,235,0.8); box-sizing:border-box;"
                                       class="dark:bg-white/5 dark:border-white/10 dark:text-white">
                            </div>
                            <div>
                                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:4px; color:#6b7280;">Tugas / PR</label>
                                <input name="homework_notes" type="text" placeholder="PR yang diberikan..."
                                       style="width:100%; padding:8px 12px; border-radius:9px; font-size:13px; font-weight:600; border:1px solid rgba(229,231,235,0.8); box-sizing:border-box;"
                                       class="dark:bg-white/5 dark:border-white/10 dark:text-white">
                            </div>
                            <div style="grid-column: span 2;">
                                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:4px; color:#6b7280;">Catatan Kegiatan</label>
                                <textarea name="execution_notes" rows="2" placeholder="Aktivitas pembelajaran..."
                                          style="width:100%; padding:8px 12px; border-radius:9px; font-size:13px; font-weight:600; border:1px solid rgba(229,231,235,0.8); resize:vertical; box-sizing:border-box;"
                                          class="dark:bg-white/5 dark:border-white/10 dark:text-white"></textarea>
                            </div>
                            <div style="grid-column: span 2;">
                                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:4px; color:#6b7280;">Kendala</label>
                                <textarea name="issues_notes" rows="2" placeholder="Kendala teknis, siswa, dll..."
                                          style="width:100%; padding:8px 12px; border-radius:9px; font-size:13px; font-weight:600; border:1px solid rgba(229,231,235,0.8); resize:vertical; box-sizing:border-box;"
                                          class="dark:bg-white/5 dark:border-white/10 dark:text-white"></textarea>
                            </div>
                            <div style="grid-column: span 2; display:flex; justify-content:flex-end;">
                                <button type="submit"
                                        style="display:inline-flex; align-items:center; gap:6px; padding:9px 20px; border-radius:10px; font-size:12px; font-weight:700; color:white; background:linear-gradient(135deg,#10b981,#059669); border:none; cursor:pointer; box-shadow:0 4px 10px -2px rgba(16,185,129,0.4); transition:all 0.2s;"
                                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 14px -2px rgba(16,185,129,0.5)';"
                                        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 10px -2px rgba(16,185,129,0.4)';">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Simpan Realisasi
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
</x-filament-panels::page>
