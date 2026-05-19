<x-filament-panels::page>

    {{-- ═══ HERO ═══════════════════════════════════════════════════════════════ --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:24px 28px; color:white; background:linear-gradient(135deg,#1d4ed8 0%,#4f46e5 50%,#7c3aed 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15);">
        <div style="position:absolute; right:-48px; top:-48px; width:224px; height:224px; border-radius:9999px; background:rgba(255,255,255,0.08); filter:blur(48px);"></div>
        <div style="position:absolute; left:-32px; bottom:-32px; width:192px; height:192px; border-radius:9999px; background:rgba(124,58,237,0.25); filter:blur(48px);"></div>
        <div style="position:relative; display:flex; align-items:center; gap:1rem;">
            <div style="width:56px; height:56px; border-radius:14px; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; border:1.5px solid rgba(255,255,255,0.3); flex-shrink:0;">
                <svg style="width:26px;height:26px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
            </div>
            <div>
                <div style="display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:9999px; background:rgba(255,255,255,0.15); font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:6px;">
                    Akademik · Laporan
                </div>
                <h2 style="font-size:22px; font-weight:800; letter-spacing:-0.025em; line-height:1.2;">Laporan Absensi Siswa</h2>
                <p style="font-size:12px; opacity:0.85; margin-top:3px;">Rekap matrix kehadiran per kelas dalam rentang tanggal</p>
            </div>
        </div>
    </div>

    {{-- ═══ FILTER ══════════════════════════════════════════════════════════════ --}}
    <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:rgba(255,255,255,0.95); padding:20px 22px; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid rgba(229,231,235,0.6);">
            <div style="width:28px; height:28px; border-radius:8px; background:linear-gradient(135deg,#1d4ed8,#4f46e5); display:flex; align-items:center; justify-content:center; color:white;">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;" class="text-gray-700 dark:text-gray-200">Filter Laporan</div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:14px; align-items:end;">
            {{-- Tanggal Mulai --}}
            <div>
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Tanggal Mulai</label>
                <input type="date" wire:model="date_from"
                    style="width:100%; border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600;"
                    class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
            </div>

            {{-- Tanggal Akhir --}}
            <div>
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Tanggal Akhir</label>
                <input type="date" wire:model="date_to"
                    style="width:100%; border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600;"
                    class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
            </div>

            {{-- Kelas --}}
            <div>
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Kelas</label>
                <select wire:model="school_class_id"
                    style="width:100%; border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600;"
                    class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    <option value="">— Pilih Kelas —</option>
                    @foreach($this->classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol --}}
            <div style="display:flex; gap:8px;">
                <button wire:click="generate"
                    style="display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:800; color:white; background:linear-gradient(135deg,#1d4ed8,#4f46e5); border:none; cursor:pointer; box-shadow:0 4px 12px -2px rgba(79,70,229,0.45); white-space:nowrap; transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Tampilkan
                </button>
                @if($show_report)
                    <button wire:click="reset_filter" title="Reset"
                        style="display:inline-flex; align-items:center; justify-content:center; padding:10px 12px; border-radius:10px; font-size:13px; color:#6b7280; background:rgba(243,244,246,0.9); border:1px solid rgba(229,231,235,0.8); cursor:pointer; transition:all 0.2s;"
                        onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='rgba(243,244,246,0.9)'">
                        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ REPORT ══════════════════════════════════════════════════════════════ --}}
    @if($show_report)
        @php $rd = $this->reportData; @endphp

        {{-- Sub-header + export buttons --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <div>
                <div style="font-size:16px; font-weight:800; color:#1e293b;" class="dark:text-white">{{ $rd['class']?->name ?? '—' }}</div>
                <div style="font-size:12px; color:#64748b; margin-top:3px;" class="dark:text-gray-400">
                    Periode: {{ \Carbon\Carbon::parse($date_from)->isoFormat('D MMMM Y') }}
                    &mdash; {{ \Carbon\Carbon::parse($date_to)->isoFormat('D MMMM Y') }}
                    &nbsp;&middot;&nbsp; <strong>{{ count($rd['dates']) }}</strong> hari
                    &nbsp;&middot;&nbsp; <strong>{{ count($rd['rows']) }}</strong> siswa
                </div>
            </div>
            <div style="display:flex; gap:8px; flex-shrink:0;">
                <button wire:click="exportExcel"
                    style="display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:10px; font-size:12px; font-weight:700; color:white; background:linear-gradient(135deg,#059669,#0d9488); border:none; cursor:pointer; box-shadow:0 3px 8px -2px rgba(5,150,105,0.45); transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Excel
                </button>
                <button wire:click="exportPdf"
                    style="display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:10px; font-size:12px; font-weight:700; color:white; background:linear-gradient(135deg,#dc2626,#b91c1c); border:none; cursor:pointer; box-shadow:0 3px 8px -2px rgba(220,38,38,0.45); transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </button>
            </div>
        </div>

        {{-- Matrix table --}}
        @if(count($rd['rows']) === 0)
            <div style="text-align:center; padding:48px 0; color:#94a3b8;">
                <svg style="width:48px;height:48px; margin:0 auto 12px; opacity:0.4;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p style="font-size:13px;">Tidak ada siswa aktif di kelas ini.</p>
            </div>
        @else
            <div style="overflow-x:auto; border-radius:14px; border:1px solid rgba(229,231,235,0.7); box-shadow:0 1px 3px rgba(0,0,0,0.06); background:white;" class="dark:bg-white/5 dark:border-white/10">
                <table style="width:100%; border-collapse:collapse; font-size:11px; white-space:nowrap;">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:2px solid rgba(229,231,235,0.8);">
                            <th style="position:sticky; left:0; z-index:10; background:#f8fafc; padding:10px 8px; text-align:center; font-weight:700; color:#475569; border-right:1px solid #e2e8f0; min-width:36px;">No</th>
                            <th style="position:sticky; left:36px; z-index:10; background:#f8fafc; padding:10px 12px; text-align:left; font-weight:700; color:#475569; border-right:1px solid #e2e8f0; min-width:160px;">Nama</th>
                            <th style="padding:10px 8px; text-align:center; font-weight:700; color:#475569; border-right:1px solid #e2e8f0; min-width:72px;">NIS</th>
                            @foreach($rd['dates'] as $date)
                                @php $d = \Carbon\Carbon::parse($date); $isWE = in_array($d->dayOfWeek,[0,6]); @endphp
                                <th style="padding:8px 4px; text-align:center; font-weight:600; color:#64748b; border-right:1px solid #f1f5f9; min-width:30px; {{ $isWE ? 'background:#f1f5f9;' : '' }}">
                                    <div style="font-size:11px;">{{ $d->format('d') }}</div>
                                    <div style="font-size:9px; font-weight:500; color:#94a3b8;">{{ $d->isoFormat('dd') }}</div>
                                </th>
                            @endforeach
                            <th style="padding:10px 8px; text-align:center; font-weight:800; color:#92400e; background:#fef3c7; border-left:2px solid #e2e8f0; min-width:32px;">S</th>
                            <th style="padding:10px 8px; text-align:center; font-weight:800; color:#1e40af; background:#dbeafe; min-width:32px;">I</th>
                            <th style="padding:10px 8px; text-align:center; font-weight:800; color:#991b1b; background:#fee2e2; min-width:32px;">A</th>
                            <th style="padding:10px 8px; text-align:center; font-weight:800; color:#1e293b; background:#f1f5f9; border-left:1px solid #e2e8f0; min-width:44px;">Hadir</th>
                            <th style="padding:10px 8px; text-align:center; font-weight:800; color:#065f46; background:#d1fae5; min-width:44px;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rd['rows'] as $i => $row)
                            <tr style="{{ $i % 2 === 0 ? 'background:white;' : 'background:#fafafa;' }}">
                                <td style="position:sticky; left:0; z-index:5; background:inherit; padding:8px; text-align:center; color:#94a3b8; border-right:1px solid #e2e8f0; font-size:10px;">{{ $row['no'] }}</td>
                                <td style="position:sticky; left:36px; z-index:5; background:inherit; padding:8px 12px; font-weight:600; color:#1e293b; border-right:1px solid #e2e8f0;" class="dark:text-white">{{ $row['student']->name }}</td>
                                <td style="padding:8px; text-align:center; color:#64748b; border-right:1px solid #e2e8f0; font-family:ui-monospace,monospace; font-size:10px;">{{ $row['student']->nis ?? '—' }}</td>

                                @foreach($rd['dates'] as $date)
                                    @php
                                        $rec    = $row['daily']->get($date);
                                        $status = $rec?->status;
                                        $dCell  = \Carbon\Carbon::parse($date);
                                        $isWEnd = in_array($dCell->dayOfWeek, [0, 6]);
                                        [$lbl, $bg, $fg] = match($status) {
                                            'hadir'     => ['H', '#d1fae5', '#065f46'],
                                            'sakit'     => ['S', '#fef3c7', '#92400e'],
                                            'izin'      => ['I', '#dbeafe', '#1e40af'],
                                            'alpa'      => ['A', '#fee2e2', '#991b1b'],
                                            'terlambat' => ['T', '#f1f5f9', '#475569'],
                                            default     => ['',  $isWEnd ? '#f1f5f9' : 'transparent', '#94a3b8'],
                                        };
                                    @endphp
                                    <td style="padding:5px 3px; text-align:center; border-right:1px solid #f1f5f9; {{ $isWEnd && !$status ? 'background:#f1f5f9;' : '' }}">
                                        @if($lbl)
                                            <span style="display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:5px; font-size:10px; font-weight:800; background:{{ $bg }}; color:{{ $fg }};">{{ $lbl }}</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td style="padding:8px; text-align:center; font-weight:700; color:#92400e; background:#fef3c7; border-left:2px solid #e2e8f0;">{{ $row['sakit'] ?: '—' }}</td>
                                <td style="padding:8px; text-align:center; font-weight:700; color:#1e40af; background:#dbeafe;">{{ $row['izin'] ?: '—' }}</td>
                                <td style="padding:8px; text-align:center; font-weight:700; color:#991b1b; background:#fee2e2;">{{ $row['alpa'] ?: '—' }}</td>
                                <td style="padding:8px; text-align:center; font-weight:700; color:#1e293b; background:#f1f5f9; border-left:1px solid #e2e8f0;">{{ $row['hadir'] }}</td>
                                <td style="padding:8px; text-align:center; font-weight:800; {{ $row['persen'] >= 75 ? 'background:#d1fae5; color:#065f46;' : 'background:#fee2e2; color:#991b1b;' }}">{{ $row['persen'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
                            <td colspan="3" style="position:sticky; left:0; z-index:5; background:#f8fafc; padding:8px 12px; text-align:right; font-size:10px; font-weight:700; color:#64748b; border-right:1px solid #e2e8f0; border-bottom:0;">Hadir per Hari</td>
                            @foreach($rd['dates'] as $date)
                                @php $dayCount = collect($rd['rows'])->filter(fn($r) => $r['daily']->has($date))->count(); @endphp
                                <td style="padding:8px 4px; text-align:center; font-size:10px; font-weight:600; color:#64748b; border-right:1px solid #f1f5f9;">{{ $dayCount ?: '' }}</td>
                            @endforeach
                            <td colspan="5" style="background:#f8fafc;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Legend --}}
            <div style="display:flex; flex-wrap:wrap; gap:10px; font-size:11px; color:#64748b;">
                @foreach([['H','#d1fae5','#065f46','Hadir'],['S','#fef3c7','#92400e','Sakit'],['I','#dbeafe','#1e40af','Izin'],['A','#fee2e2','#991b1b','Alpa'],['T','#f1f5f9','#475569','Terlambat (= Hadir)']] as [$l,$bg,$fg,$txt])
                    <span style="display:inline-flex; align-items:center; gap:5px;">
                        <span style="display:inline-flex; align-items:center; justify-content:center; width:20px; height:20px; border-radius:4px; font-size:9px; font-weight:800; background:{{ $bg }}; color:{{ $fg }};">{{ $l }}</span>
                        {{ $txt }}
                    </span>
                @endforeach
                <span style="display:inline-flex; align-items:center; gap:5px; color:#94a3b8;">
                    <span style="display:inline-block; width:20px; height:20px; border-radius:4px; background:#f1f5f9;"></span>
                    Kosong = tidak ada data / libur
                </span>
            </div>
        @endif

    @else
        {{-- Empty state --}}
        <div style="text-align:center; padding:64px 24px; border-radius:16px; border:2px dashed rgba(203,213,225,0.6); background:rgba(248,250,252,0.5);" class="dark:border-white/10 dark:bg-white/5">
            <div style="width:64px; height:64px; border-radius:18px; background:linear-gradient(135deg,rgba(29,78,216,0.12),rgba(79,70,229,0.08)); display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                <svg style="width:28px;height:28px; color:#1d4ed8;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
            </div>
            <div style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:6px;" class="dark:text-white">Laporan Belum Ditampilkan</div>
            <div style="font-size:12px; color:#64748b;" class="dark:text-gray-400">Pilih <strong>rentang tanggal</strong> dan <strong>kelas</strong>, lalu klik <strong>Tampilkan</strong>.</div>
        </div>
    @endif

</x-filament-panels::page>
