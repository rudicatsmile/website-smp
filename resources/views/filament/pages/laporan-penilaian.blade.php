<x-filament-panels::page>

    @php
        $data       = $this->reportData;
        $tpSessions = $data['tpSessions'] ?? collect();
        $examTypes  = $data['examTypes']  ?? collect();
        $rows       = $data['rows']       ?? [];
        $class      = $data['class']      ?? null;
        $subject    = $data['subject']    ?? null;
        $typeLabels = \App\Models\ExamSession::TYPES;
    @endphp

    {{-- ═══ HERO ═══ --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:22px 28px; color:white; background:linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 55%,#3b82f6 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15); margin-bottom:0;">
        <div style="position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:9999px; background:rgba(255,255,255,0.06); filter:blur(50px);"></div>
        <div style="position:relative;">
            <div style="font-size:10px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; opacity:0.75; margin-bottom:6px;">Laporan Akademik</div>
            <div style="font-size:22px; font-weight:800; letter-spacing:-0.02em; margin-bottom:4px;">Penilaian Peserta Didik</div>
            @if($this->show_report && $class && $subject)
                <div style="font-size:12px; opacity:0.85; display:flex; gap:12px; flex-wrap:wrap;">
                    <span>{{ $class->name }}</span>
                    <span>&middot;</span>
                    <span>{{ $subject->name }}</span>
                    <span>&middot;</span>
                    <span>TA {{ $this->academic_year }}</span>
                    <span>&middot;</span>
                    <span>{{ count($rows) }} Siswa</span>
                    <span>&middot;</span>
                    <span>{{ $tpSessions->count() }} Sesi TP</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══ FILTER CARD ═══ --}}
    <div style="border-radius:16px; border:1px solid #e2e8f0; background:white; padding:20px 24px; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
            <div style="width:8px; height:8px; border-radius:50%; background:#3b82f6;"></div>
            <span style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:#64748b;">Filter Laporan</span>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; align-items:end;">

            {{-- Kelas --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#374151; margin-bottom:6px;">Kelas <span style="color:#ef4444;">*</span></label>
                <select wire:model="school_class_id" style="width:100%; border-radius:8px; padding:8px 12px; font-size:13px; border:1.5px solid #e2e8f0; background:white; color:#374151; outline:none;">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($this->classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
                @error('school_class_id')<div style="color:#ef4444;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            {{-- Mata Pelajaran --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#374151; margin-bottom:6px;">Mata Pelajaran <span style="color:#ef4444;">*</span></label>
                <select wire:model="material_category_id" style="width:100%; border-radius:8px; padding:8px 12px; font-size:13px; border:1.5px solid #e2e8f0; background:white; color:#374151; outline:none;">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($this->subjects as $subj)
                        <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                    @endforeach
                </select>
                @error('material_category_id')<div style="color:#ef4444;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            {{-- Tahun Ajaran --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#374151; margin-bottom:6px;">Tahun Ajaran <span style="color:#ef4444;">*</span></label>
                <input type="text" wire:model="academic_year" placeholder="2025/2026"
                    style="width:100%; border-radius:8px; padding:8px 12px; font-size:13px; border:1.5px solid #e2e8f0; background:white; color:#374151; outline:none; box-sizing:border-box;">
                @error('academic_year')<div style="color:#ef4444;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            {{-- Actions --}}
            <div style="display:flex; gap:8px; align-items:center;">
                <button wire:click="generate"
                    style="flex:1; padding:9px 20px; border-radius:8px; font-size:13px; font-weight:700; color:white; background:#1d4ed8; border:none; cursor:pointer;">
                    Tampilkan
                </button>
                @if($this->show_report)
                    <button wire:click="reset_filter"
                        style="padding:9px 14px; border-radius:8px; font-size:12px; font-weight:600; color:#475569; background:#f1f5f9; border:1px solid #e2e8f0; cursor:pointer;">
                        Reset
                    </button>
                @endif
            </div>
        </div>

        {{-- Export buttons --}}
        @if($this->show_report && count($rows) > 0)
            <div style="margin-top:16px; padding-top:14px; border-top:1px solid #f1f5f9; display:flex; gap:8px; flex-wrap:wrap;">
                <span style="font-size:11px; font-weight:700; color:#94a3b8; align-self:center; margin-right:4px;">Export:</span>
                <button wire:click="exportExcel"
                    style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:8px; font-size:12px; font-weight:700; color:#065f46; background:#d1fae5; border:1px solid #6ee7b7; cursor:pointer;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                </button>
                <button wire:click="exportPdf"
                    style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:8px; font-size:12px; font-weight:700; color:#991b1b; background:#fee2e2; border:1px solid #fca5a5; cursor:pointer;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </button>
            </div>
        @endif
    </div>

    {{-- ═══ REPORT TABLE ═══ --}}
    @if($this->show_report)
        @if(empty($rows))
            <div style="text-align:center; padding:60px 20px; color:#94a3b8; font-size:13px; background:white; border-radius:16px; border:1px solid #e2e8f0;">
                <div style="font-size:32px; margin-bottom:10px;">📋</div>
                Tidak ada siswa aktif di kelas ini, atau belum ada data penilaian.
            </div>
        @else
            <div style="border-radius:16px; border:1px solid #e2e8f0; background:white; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">

                {{-- Legend --}}
                <div style="padding:10px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:16px; flex-wrap:wrap; font-size:11px;">
                    <span style="font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em;">Keterangan:</span>
                    <span style="display:inline-flex; align-items:center; gap:5px;">
                        <span style="display:inline-block; width:28px; height:14px; border-radius:3px; background:#dbeafe; border:1px solid #93c5fd;"></span>
                        <span style="color:#1e40af;">TP = Tujuan Pembelajaran (Harian)</span>
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:5px;">
                        <span style="display:inline-block; width:28px; height:14px; border-radius:3px; background:#ffedd5; border:1px solid #fdba74;"></span>
                        <span style="color:#9a3412;">Sumatif Lingkup Materi (Ujian)</span>
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:5px;">
                        <span style="display:inline-block; width:28px; height:14px; border-radius:3px; background:#dcfce7; border:1px solid #86efac;"></span>
                        <span style="color:#166534;">Sumatif Akhir Semester</span>
                    </span>
                </div>

                {{-- Horizontal scroll wrapper --}}
                <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                    <table style="width:100%; border-collapse:collapse; font-size:11.5px; white-space:nowrap;">
                        <thead>
                            {{-- Row 1: Group headers --}}
                            <tr style="background:#1e3a8a; color:white;">
                                <th rowspan="2" style="padding:10px 8px; border:1px solid #1e40af; text-align:center; min-width:36px;">No</th>
                                <th rowspan="2" style="padding:10px 10px; border:1px solid #1e40af; text-align:center; min-width:80px;">NIS</th>
                                <th rowspan="2" style="padding:10px 14px; border:1px solid #1e40af; text-align:left; min-width:180px;">Nama Peserta Didik</th>

                                @if($tpSessions->count() > 0)
                                    <th colspan="{{ $tpSessions->count() }}" style="padding:8px 14px; border:1px solid #1e40af; text-align:center; background:#1d4ed8;">
                                        Tujuan Pembelajaran (TP)
                                    </th>
                                @endif

                                @if($examTypes->count() > 0)
                                    <th colspan="{{ $examTypes->count() }}" style="padding:8px 14px; border:1px solid #c2410c; text-align:center; background:#ea580c;">
                                        Sumatif Lingkup Materi
                                    </th>
                                @endif

                                <th colspan="2" style="padding:8px 14px; border:1px solid #15803d; text-align:center; background:#16a34a;">
                                    Sumatif Akhir
                                </th>
                            </tr>

                            {{-- Row 2: Sub-headers --}}
                            <tr style="background:#1e3a8a; color:white;">
                                @foreach($tpSessions as $idx => $session)
                                    <th style="padding:7px 8px; border:1px solid #1e40af; text-align:center; background:#1d4ed8; min-width:60px; font-size:10px;">
                                        TP-{{ $idx + 1 }}
                                        <div style="font-size:9px; opacity:0.7; font-weight:400;">{{ \Carbon\Carbon::parse($session->session_date)->format('d/m') }}</div>
                                    </th>
                                @endforeach

                                @foreach($examTypes as $type)
                                    <th style="padding:7px 8px; border:1px solid #c2410c; text-align:center; background:#ea580c; min-width:60px; font-size:10px;">
                                        {{ $typeLabels[$type] ?? strtoupper($type) }}
                                    </th>
                                @endforeach

                                <th style="padding:7px 8px; border:1px solid #15803d; text-align:center; background:#16a34a; min-width:60px; font-size:10px;">Sem 1</th>
                                <th style="padding:7px 8px; border:1px solid #15803d; text-align:center; background:#16a34a; min-width:60px; font-size:10px;">Sem 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                                @php $bgBase = $loop->odd ? 'white' : '#f8fafc'; @endphp
                                <tr style="background:{{ $bgBase }};" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='{{ $bgBase }}'">
                                    <td style="padding:8px 8px; border:1px solid #e2e8f0; text-align:center; color:#94a3b8; font-size:10px;">{{ $row['no'] }}</td>
                                    <td style="padding:8px 10px; border:1px solid #e2e8f0; text-align:center; color:#64748b; font-family:ui-monospace,monospace; font-size:10px;">{{ $row['student']->nis ?? '—' }}</td>
                                    <td style="padding:8px 14px; border:1px solid #e2e8f0; font-weight:600; color:#1e293b;">{{ $row['student']->name }}</td>

                                    {{-- TP scores --}}
                                    @foreach($tpSessions as $session)
                                        @php $val = $row['tpScores'][$session->id] ?? null; @endphp
                                        <td style="padding:8px 8px; border:1px solid #e2e8f0; text-align:center; background:{{ $val !== null ? '#eff6ff' : $bgBase }}; font-weight:{{ $val !== null ? '600' : '400' }}; color:{{ $val !== null ? '#1e40af' : '#cbd5e1' }};">
                                            {{ $val !== null ? number_format($val, 1) : '—' }}
                                        </td>
                                    @endforeach

                                    {{-- Exam scores by type --}}
                                    @foreach($examTypes as $type)
                                        @php $val = $row['examScoresByType'][$type] ?? null; @endphp
                                        <td style="padding:8px 8px; border:1px solid #e2e8f0; text-align:center; background:{{ $val !== null ? '#fff7ed' : $bgBase }}; font-weight:{{ $val !== null ? '600' : '400' }}; color:{{ $val !== null ? '#9a3412' : '#cbd5e1' }};">
                                            {{ $val !== null ? number_format($val, 1) : '—' }}
                                        </td>
                                    @endforeach

                                    {{-- Sumatif Akhir --}}
                                    @php
                                        $s1 = $row['sem1'];
                                        $s2 = $row['sem2'];
                                    @endphp
                                    <td style="padding:8px 8px; border:1px solid #e2e8f0; text-align:center; background:{{ $s1 !== null ? '#f0fdf4' : $bgBase }}; font-weight:700; color:{{ $s1 !== null ? '#166534' : '#cbd5e1' }};">
                                        {{ $s1 !== null ? number_format($s1, 1) : '—' }}
                                    </td>
                                    <td style="padding:8px 8px; border:1px solid #e2e8f0; text-align:center; background:{{ $s2 !== null ? '#f0fdf4' : $bgBase }}; font-weight:700; color:{{ $s2 !== null ? '#166534' : '#cbd5e1' }};">
                                        {{ $s2 !== null ? number_format($s2, 1) : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            {{-- Rata-rata kelas --}}
                            <tr style="background:#f8fafc; font-weight:700; border-top:2px solid #e2e8f0;">
                                <td colspan="3" style="padding:9px 14px; border:1px solid #e2e8f0; text-align:right; font-size:11px; color:#475569; font-weight:800;">Rata-rata Kelas</td>

                                @foreach($tpSessions as $session)
                                    @php
                                        $vals = collect($rows)->pluck("tpScores.{$session->id}")->filter(fn($v) => $v !== null);
                                        $avg  = $vals->isNotEmpty() ? round($vals->avg(), 1) : null;
                                    @endphp
                                    <td style="padding:9px 8px; border:1px solid #e2e8f0; text-align:center; background:#dbeafe; color:#1e40af; font-size:11px;">
                                        {{ $avg !== null ? number_format($avg, 1) : '—' }}
                                    </td>
                                @endforeach

                                @foreach($examTypes as $type)
                                    @php
                                        $vals = collect($rows)->pluck("examScoresByType.{$type}")->filter(fn($v) => $v !== null);
                                        $avg  = $vals->isNotEmpty() ? round($vals->avg(), 1) : null;
                                    @endphp
                                    <td style="padding:9px 8px; border:1px solid #e2e8f0; text-align:center; background:#ffedd5; color:#9a3412; font-size:11px;">
                                        {{ $avg !== null ? number_format($avg, 1) : '—' }}
                                    </td>
                                @endforeach

                                @php
                                    $avgS1 = collect($rows)->pluck('sem1')->filter(fn($v) => $v !== null);
                                    $avgS2 = collect($rows)->pluck('sem2')->filter(fn($v) => $v !== null);
                                @endphp
                                <td style="padding:9px 8px; border:1px solid #e2e8f0; text-align:center; background:#dcfce7; color:#166534; font-size:11px;">
                                    {{ $avgS1->isNotEmpty() ? number_format($avgS1->avg(), 1) : '—' }}
                                </td>
                                <td style="padding:9px 8px; border:1px solid #e2e8f0; text-align:center; background:#dcfce7; color:#166534; font-size:11px;">
                                    {{ $avgS2->isNotEmpty() ? number_format($avgS2->avg(), 1) : '—' }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Footer info --}}
                <div style="padding:10px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; font-size:11px; color:#94a3b8; display:flex; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                    <span>{{ count($rows) }} siswa &middot; {{ $tpSessions->count() }} sesi TP &middot; {{ $examTypes->count() }} jenis ujian</span>
                    <span>Nilai = rata-rata dari semua penilaian di sesi/jenis tersebut</span>
                </div>
            </div>
        @endif
    @endif

</x-filament-panels::page>
