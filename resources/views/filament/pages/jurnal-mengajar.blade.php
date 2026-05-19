<x-filament-panels::page>

    {{-- ═══ HERO ═══════════════════════════════════════════════════════════════ --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:24px 28px; color:white; background:linear-gradient(135deg,#0f4c81 0%,#1565c0 50%,#1976d2 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.18);">
        <div style="position:absolute; right:-48px; top:-48px; width:224px; height:224px; border-radius:9999px; background:rgba(255,255,255,0.07); filter:blur(48px);"></div>
        <div style="position:absolute; left:-32px; bottom:-32px; width:192px; height:192px; border-radius:9999px; background:rgba(25,118,210,0.3); filter:blur(48px);"></div>
        <div style="position:relative; display:flex; align-items:center; gap:1rem;">
            <div style="width:56px; height:56px; border-radius:14px; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; border:1.5px solid rgba(255,255,255,0.3); flex-shrink:0;">
                <svg style="width:26px;height:26px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <div style="display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:9999px; background:rgba(255,255,255,0.15); font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:6px;">
                    Akademik · Jurnal
                </div>
                <h2 style="font-size:22px; font-weight:800; letter-spacing:-0.025em; line-height:1.2;">Jurnal Mengajar Pendidik</h2>
                <p style="font-size:12px; opacity:0.85; margin-top:3px;">Rekap kegiatan pembelajaran per sesi — mata pelajaran, materi, dan kehadiran siswa</p>
            </div>
        </div>
    </div>

    {{-- ═══ FILTER ══════════════════════════════════════════════════════════════ --}}
    <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:rgba(255,255,255,0.95); padding:20px 22px; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid rgba(229,231,235,0.6);">
            <div style="width:28px; height:28px; border-radius:8px; background:linear-gradient(135deg,#0f4c81,#1565c0); display:flex; align-items:center; justify-content:center; color:white;">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;" class="text-gray-700 dark:text-gray-200">Filter Jurnal</div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr auto; gap:14px; align-items:end;">
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

            {{-- Mata Pelajaran --}}
            <div>
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Mata Pelajaran</label>
                <select wire:model="material_category_id"
                    style="width:100%; border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600;"
                    class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    <option value="">— Semua Mapel —</option>
                    @foreach($this->subjects as $subj)
                        <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Kelas --}}
            <div>
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Kelas</label>
                <select wire:model="school_class_id"
                    style="width:100%; border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600;"
                    class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    <option value="">— Semua Kelas —</option>
                    @foreach($this->classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol --}}
            <div style="display:flex; gap:8px;">
                <button wire:click="generate"
                    style="display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:800; color:white; background:linear-gradient(135deg,#0f4c81,#1565c0); border:none; cursor:pointer; box-shadow:0 4px 12px -2px rgba(15,76,129,0.45); white-space:nowrap; transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Tampilkan
                </button>
                @if($show_report)
                    <button wire:click="reset_filter" title="Reset"
                        style="display:inline-flex; align-items:center; justify-content:center; padding:10px 12px; border-radius:10px; color:#6b7280; background:rgba(243,244,246,0.9); border:1px solid rgba(229,231,235,0.8); cursor:pointer; transition:all 0.2s;"
                        onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='rgba(243,244,246,0.9)'">
                        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- Tahun Pelajaran (baris kedua) --}}
        <div style="margin-top:14px; padding-top:14px; border-top:1px solid rgba(229,231,235,0.5); display:flex; align-items:center; gap:14px;">
            <label style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; white-space:nowrap;" class="text-gray-500 dark:text-gray-400">Tahun Pelajaran</label>
            <select wire:model="academic_year"
                style="border-radius:10px; padding:7px 12px; font-size:13px; font-weight:600; min-width:180px;"
                class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
                <option value="">— Semua Tahun —</option>
                @foreach($this->academicYears as $yr)
                    <option value="{{ $yr }}">{{ $yr }}</option>
                @endforeach
            </select>
            <span style="font-size:11px;" class="text-gray-400 dark:text-gray-500">(Opsional — berdasarkan kurikulum yang ditautkan ke sesi)</span>
        </div>
    </div>

    {{-- ═══ REPORT ══════════════════════════════════════════════════════════════ --}}
    @if($show_report)
        @php $rd = $this->reportData; $rows = $rd['rows']; $meta = $rd['meta']; @endphp

        {{-- Sub-header + export --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <div>
                <div style="font-size:16px; font-weight:800; color:#1e293b;" class="dark:text-white">
                    Jurnal Mengajar
                    @if($meta['class']) · {{ $meta['class']->name }} @endif
                    @if($meta['subject']) · {{ $meta['subject']->name }} @endif
                    @if($meta['academic_year']) · TA {{ $meta['academic_year'] }} @endif
                </div>
                <div style="font-size:12px; color:#64748b; margin-top:3px;" class="dark:text-gray-400">
                    Periode: {{ \Carbon\Carbon::parse($date_from)->isoFormat('D MMMM Y') }}
                    &mdash; {{ \Carbon\Carbon::parse($date_to)->isoFormat('D MMMM Y') }}
                    &nbsp;&middot;&nbsp; <strong>{{ $meta['total'] }}</strong> sesi
                </div>
            </div>
            @if(count($rows) > 0)
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
            @endif
        </div>

        {{-- Table --}}
        @if(count($rows) === 0)
            <div style="text-align:center; padding:48px 0; color:#94a3b8;">
                <svg style="width:48px;height:48px; margin:0 auto 12px; opacity:0.4;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p style="font-size:13px;">Tidak ada sesi mengajar yang sesuai filter.</p>
            </div>
        @else
            <div style="overflow-x:auto; border-radius:14px; border:1px solid rgba(229,231,235,0.7); box-shadow:0 1px 3px rgba(0,0,0,0.06); background:white;" class="dark:bg-white/5 dark:border-white/10">
                <table style="width:100%; border-collapse:collapse; font-size:12px;">
                    <thead>
                        <tr style="background:#f0f4f8; border-bottom:2px solid #d0dde8;">
                            <th style="padding:11px 10px; text-align:center; font-weight:700; color:#1e40af; min-width:32px;">No</th>
                            <th style="padding:11px 12px; text-align:left; font-weight:700; color:#1e40af; min-width:160px;">Hari & Tanggal</th>
                            <th style="padding:11px 10px; text-align:center; font-weight:700; color:#1e40af; min-width:80px; border-left:1px solid #d0dde8;">Minggu<br>Pertemuan Ke</th>
                            <th style="padding:11px 12px; text-align:left; font-weight:700; color:#1e40af; min-width:200px; border-left:1px solid #d0dde8;">Bahasan Materi</th>
                            <th style="padding:11px 10px; text-align:center; font-weight:700; color:#1e40af; min-width:90px; border-left:1px solid #d0dde8;">Jumlah Siswa<br>Hadir</th>
                            <th style="padding:11px 12px; text-align:left; font-weight:700; color:#1e40af; min-width:180px; border-left:1px solid #d0dde8;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $i => $row)
                            <tr style="{{ $i % 2 === 0 ? 'background:white;' : 'background:#f8fafc;' }} border-bottom:1px solid #e8eef4;">
                                <td style="padding:10px; text-align:center; color:#94a3b8; font-size:11px;">{{ $row['no'] }}</td>
                                <td style="padding:10px 12px;">
                                    <div style="font-weight:700; color:#1e293b;" class="dark:text-white">{{ $row['date_label'] }}</div>
                                    <div style="font-size:10px; color:#64748b; margin-top:2px;">
                                        {{ $row['class_name'] }}
                                        @if($row['session']->start_time)
                                            &middot; {{ substr($row['session']->start_time, 0, 5) }}–{{ substr($row['session']->end_time ?? '', 0, 5) }}
                                        @endif
                                    </div>
                                    <div style="font-size:10px; color:#0f4c81; margin-top:1px; font-weight:600;">{{ $row['subject_name'] }}</div>
                                </td>
                                <td style="padding:10px; text-align:center; border-left:1px solid #e8eef4;">
                                    @if($row['week_number'])
                                        <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#dbeafe,#bfdbfe); color:#1d4ed8; font-weight:800; font-size:15px;">{{ $row['week_number'] }}</span>
                                    @else
                                        <span style="color:#cbd5e1; font-size:11px;">—</span>
                                    @endif
                                </td>
                                <td style="padding:10px 12px; border-left:1px solid #e8eef4;">
                                    <div style="font-weight:600; color:#1e293b; line-height:1.4;" class="dark:text-white">{{ $row['topic'] }}</div>
                                    @if($row['session']->learning_objectives)
                                        <div style="font-size:10px; color:#64748b; margin-top:4px; line-height:1.4;">{{ \Illuminate\Support\Str::limit($row['session']->learning_objectives, 80) }}</div>
                                    @endif
                                </td>
                                <td style="padding:10px; text-align:center; border-left:1px solid #e8eef4;">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:40px; padding:4px 10px; border-radius:20px; font-weight:800; font-size:14px; {{ $row['hadir'] > 0 ? 'background:#d1fae5; color:#065f46;' : 'background:#f1f5f9; color:#94a3b8;' }}">
                                        {{ $row['hadir'] }}
                                    </span>
                                </td>
                                <td style="padding:10px 12px; border-left:1px solid #e8eef4; color:#475569; font-size:11px; line-height:1.5;" class="dark:text-gray-300">
                                    {{ $row['notes'] ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f0f4f8; border-top:2px solid #d0dde8;">
                            <td colspan="2" style="padding:10px 12px; font-weight:700; color:#1e40af; font-size:11px;">Total: {{ count($rows) }} sesi</td>
                            <td style="padding:10px; border-left:1px solid #d0dde8;"></td>
                            <td style="padding:10px 12px; border-left:1px solid #d0dde8;"></td>
                            <td style="padding:10px; text-align:center; border-left:1px solid #d0dde8; font-weight:800; color:#065f46;">
                                {{ collect($rows)->sum('hadir') }}
                            </td>
                            <td style="border-left:1px solid #d0dde8;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

    @else
        {{-- Empty state --}}
        <div style="text-align:center; padding:64px 24px; border-radius:16px; border:2px dashed rgba(203,213,225,0.6); background:rgba(248,250,252,0.5);" class="dark:border-white/10 dark:bg-white/5">
            <div style="width:64px; height:64px; border-radius:18px; background:linear-gradient(135deg,rgba(15,76,129,0.12),rgba(21,101,192,0.08)); display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                <svg style="width:28px;height:28px; color:#1565c0;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:6px;" class="dark:text-white">Jurnal Belum Ditampilkan</div>
            <div style="font-size:12px; color:#64748b;" class="dark:text-gray-400">Atur filter dan klik <strong>Tampilkan</strong> untuk memuat jurnal mengajar.</div>
        </div>
    @endif

</x-filament-panels::page>
