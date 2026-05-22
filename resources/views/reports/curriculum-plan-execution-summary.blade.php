<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Realisasi – {{ $plan->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            background: #f0f4f8;
            padding: 20px;
        }

        .page {
            background: #fff;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm 20mm 16mm 20mm;
            box-shadow: 0 2px 20px rgba(0,0,0,0.12);
            border-radius: 4px;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .header img {
            height: 64px;
            width: auto;
            object-fit: contain;
        }
        .header-text { flex: 1; }
        .school-name {
            font-size: 16pt;
            font-weight: 700;
            color: #1e3a8a;
            letter-spacing: 0.3px;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: 600;
            color: #374151;
            margin-top: 2px;
        }
        .doc-subtitle {
            font-size: 9pt;
            color: #6b7280;
            margin-top: 3px;
        }

        /* ── Info Section ── */
        .info-section {
            background: #f8faff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 18px;
        }
        .info-section h2 {
            font-size: 10pt;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-bottom: 1px solid #bfdbfe;
            padding-bottom: 6px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 20px;
        }
        .info-row {
            display: flex;
            gap: 6px;
            align-items: flex-start;
            line-height: 1.5;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
            min-width: 130px;
            font-size: 10pt;
        }
        .info-label::after { content: ':'; }
        .info-value {
            color: #111827;
            font-size: 10pt;
        }

        /* ── Stats Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 18px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }
        .stat-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-card.blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .stat-card.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .stat-value {
            font-size: 24pt;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-label {
            font-size: 9pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }

        /* ── Table ── */
        .sessions-section h2 {
            font-size: 10pt;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        table.sessions {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        table.sessions thead tr {
            background: #1e3a8a;
            color: #fff;
        }
        table.sessions thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9pt;
            border: 1px solid #1e3a8a;
        }
        table.sessions tbody tr:nth-child(even) { background: #f0f6ff; }
        table.sessions tbody tr:hover { background: #dbeafe; }
        table.sessions tbody td {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            line-height: 1.4;
        }
        table.sessions td.center {
            text-align: center;
            font-weight: 700;
        }
        table.sessions .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 8.5pt;
            font-weight: 600;
        }
        table.sessions .badge.completed {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        table.sessions .badge.published {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #93c5fd;
        }
        table.sessions .achievement {
            font-weight: 700;
            text-align: center;
        }
        table.sessions .achievement.high { color: #15803d; }
        table.sessions .achievement.medium { color: #b45309; }
        table.sessions .achievement.low { color: #b91c1c; }
        table.sessions .achievement.empty { color: #9ca3af; }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .signature-box {
            text-align: center;
            min-width: 160px;
        }
        .signature-box .sign-label {
            font-size: 9.5pt;
            color: #374151;
        }
        .signature-box .sign-space {
            height: 50px;
            border-bottom: 1px solid #374151;
            margin: 8px 10px 4px;
        }
        .signature-box .sign-name {
            font-size: 9pt;
            color: #1f2937;
            font-weight: 600;
        }

        /* ── Print styles ── */
        @media print {
            body { background: #fff; padding: 0; }
            .page { box-shadow: none; border-radius: 0; max-width: 100%; padding: 12mm 15mm; }
            .no-print { display: none !important; }
            table.sessions { page-break-inside: auto; }
            table.sessions tr { page-break-inside: avoid; }
        }

        /* ── Print button ── */
        .toolbar {
            max-width: 210mm;
            margin: 0 auto 16px;
            display: flex;
            gap: 10px;
        }
        .btn-print {
            background: #1e40af;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 11pt;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print:hover { background: #1d3a9e; }
        .btn-close {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 11pt;
            cursor: pointer;
        }
        .btn-close:hover { background: #e5e7eb; }

        /* ── Explicit Sections ── */
        .sections-container {
            margin-top: 20px;
        }
        .section-group {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 10pt;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .section-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .content-item {
            background: #f9fafb;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
            padding: 10px 12px;
            font-size: 10pt;
            line-height: 1.5;
            text-align: left;
        }
        .content-item.green { border-left-color: #10b981; }
        .content-item.orange { border-left-color: #f59e0b; }
        .content-item.red { border-left-color: #ef4444; }
        .content-item-title {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 3px;
        }
        .content-item-desc {
            color: #6b7280;
            font-size: 9.5pt;
        }
        .content-item.empty {
            color: #9ca3af;
            font-style: italic;
            background: #f3f4f6;
            border-left-color: #d1d5db;
        }

        /* ── Attendance Table ── */
        table.attendance-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-top: 10px;
        }
        table.attendance-table thead tr {
            background: #1e3a8a;
            color: #fff;
        }
        table.attendance-table thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9pt;
            border: 1px solid #1e3a8a;
        }
        table.attendance-table tbody tr:nth-child(even) { background: #f0f6ff; }
        table.attendance-table tbody tr:hover { background: #dbeafe; }
        table.attendance-table tbody td {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            text-align: center;
            font-weight: 600;
        }
        table.attendance-table td:first-child {
            text-align: left;
            font-weight: normal;
        }
    </style>
</head>
<body>

<div class="toolbar no-print">
    <button class="btn-print" onclick="window.print()">
        🖨️ Cetak / Simpan PDF
    </button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
</div>

<div class="page">

    {{-- ── Header ── --}}
    <div class="header">
        @if($schoolLogo)
            <img src="{{ asset('storage/' . $schoolLogo) }}" alt="{{ $schoolName }}">
        @endif
        <div class="header-text">
            <div class="school-name">{{ $schoolName }}</div>
            <div class="doc-title">LAPORAN REALISASI PEMBELAJARAN</div>
            <div class="doc-subtitle">
                {{ $plan->academic_year }} &bull; Semester {{ ucfirst($plan->semester ?? '') }}
            </div>
        </div>
    </div>

    {{-- ── Informasi Plan ── --}}
    <div class="info-section">
        <h2>Informasi Rencana Pembelajaran</h2>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Guru Pengampu</span>
                <span class="info-value">{{ $plan->teacher?->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mata Pelajaran</span>
                <span class="info-value">{{ $plan->subject?->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span class="info-value">{{ $plan->schoolClass?->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tahun Ajaran</span>
                <span class="info-value">{{ $plan->academic_year }}</span>
            </div>
            <div class="info-row" style="grid-column: span 2;">
                <span class="info-label">Topik / Judul</span>
                <span class="info-value">{{ $plan->title }}</span>
            </div>
        </div>
    </div>

    {{-- ── Statistik ── --}}
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-value">{{ $totalSessions }}</div>
            <div class="stat-label">Total Sesi</div>
        </div>
        <div class="stat-card green">
            <div class="stat-value">{{ $completedCount }}</div>
            <div class="stat-label">Sesi Selesai</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-value">{{ $avgAchievement }}%</div>
            <div class="stat-label">Rata-rata Pencapaian</div>
        </div>
    </div>

    {{-- ── Tabel Sesi ── --}}
    <div class="sessions-section">
        <h2>Rincian Sesi Pembelajaran ({{ $totalSessions }} Sesi)</h2>

        @if($sessionsData->isEmpty())
            <p style="color:#9ca3af; font-style:italic; padding: 12px 0;">Belum ada sesi yang dibuat.</p>
        @else
        <table class="sessions">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th style="width:18%;">Tanggal</th>
                    <th style="width:28%;">Topik / Materi</th>
                    <th style="width:12%;">Pencapaian</th>
                    <th style="width:15%;">Status</th>
                    <th style="width:27%;">Catatan Singkat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessionsData as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item['date_label'] }}</strong><br>
                        <small style="color:#6b7280;">{{ $item['time_range'] }}</small>
                    </td>
                    <td>{{ $item['topic'] }}</td>
                    <td class="achievement {{ $item['achievement'] ? ($item['achievement'] >= 85 ? 'high' : ($item['achievement'] >= 75 ? 'medium' : 'low')) : 'empty' }}">
                        @if($item['achievement'] !== null)
                            {{ $item['achievement'] }}%
                        @else
                            —
                        @endif
                    </td>
                    <td class="center">
                        <span class="badge {{ $item['status'] === 'completed' ? 'completed' : 'published' }}">
                            {{ $item['status_label'] }}
                        </span>
                    </td>
                    <td>
                        @if($item['execution_notes'])
                            <small>{{ Str::limit($item['execution_notes'], 80) }}</small>
                        @else
                            <small style="color:#9ca3af;">—</small>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- ── Komponen Pembelajaran & Absensi (Explicit Display) ── --}}
    <div class="sections-container">

        {{-- Absensi Section --}}
        <div class="section-group">
            <h2 class="section-title">📊 Ringkasan Absensi per Sesi</h2>
            @if($sessionsData->isNotEmpty())
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Hadir</th>
                            <th>Terlambat</th>
                            <th>Sakit</th>
                            <th>Izin</th>
                            <th>Alfa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessionsData as $item)
                        <tr>
                            <td>{{ $item['date_label'] }}</td>
                            <td>{{ $item['attendance']['hadir'] ?? 0 }}</td>
                            <td>{{ $item['attendance']['terlambat'] ?? 0 }}</td>
                            <td>{{ $item['attendance']['sakit'] ?? 0 }}</td>
                            <td>{{ $item['attendance']['izin'] ?? 0 }}</td>
                            <td>{{ $item['attendance']['alfa'] ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="content-item empty">Belum ada data absensi</div>
            @endif
        </div>

        {{-- Materials Section --}}
        <div class="section-group">
            <h2 class="section-title">📚 Materi Pembelajaran</h2>
            <div class="section-content">
                @if($allMaterials->isNotEmpty())
                    @foreach($allMaterials as $material)
                        <div class="content-item">
                            <div class="content-item-title">{{ $material->title }}</div>
                            @if($material->description)
                                <div class="content-item-desc">{{ $material->description }}</div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="content-item empty">Belum ada materi yang ditambahkan</div>
                @endif
            </div>
        </div>

        {{-- Assignments Section --}}
        <div class="section-group">
            <h2 class="section-title">✏️ Tugas / Pekerjaan Rumah</h2>
            <div class="section-content">
                @if($allAssignments->isNotEmpty())
                    @foreach($allAssignments as $assignment)
                        <div class="content-item green">
                            <div class="content-item-title">{{ $assignment->title }}</div>
                            <div class="content-item-desc">
                                Tenggat: {{ $assignment->due_at?->isoFormat('D MMMM Y') ?? '—' }} | Skor Maks: {{ $assignment->max_score ?? '—' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="content-item empty">Belum ada tugas yang ditambahkan</div>
                @endif
            </div>
        </div>

        {{-- Assessments Section --}}
        <div class="section-group">
            <h2 class="section-title">📋 Penilaian / Assessment</h2>
            <div class="section-content">
                @if($allAssessments->isNotEmpty())
                    @foreach($allAssessments as $assessment)
                        <div class="content-item orange">
                            <div class="content-item-title">{{ $assessment->title }}</div>
                            <div class="content-item-desc">
                                Jenis: {{ $assessment->type ?? '—' }} | Skor Maks: {{ $assessment->max_score ?? '—' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="content-item empty">Belum ada penilaian yang ditambahkan</div>
                @endif
            </div>
        </div>

        {{-- Cases Section --}}
        <div class="section-group">
            <h2 class="section-title">⚠️ Kasus Peserta Didik</h2>
            <div class="section-content">
                @if($allCases->isNotEmpty())
                    @foreach($allCases as $case)
                        <div class="content-item red">
                            <div class="content-item-title">{{ $case->student?->name ?? '—' }}</div>
                            <div class="content-item-desc">
                                {{ $case->description }} | Status: {{ $case->status_label ?? '—' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="content-item empty">Belum ada kasus yang dicatat</div>
                @endif
            </div>
        </div>

    </div>

    {{-- ── Footer / Tanda Tangan ── --}}
    <div class="footer">
        <div>
            <p style="font-size:9pt; color:#6b7280;">Dicetak: {{ now()->isoFormat('dddd, D MMMM Y, HH:mm') }}</p>
        </div>
        <div class="signature-box">
            <p class="sign-label">Guru Pengampu,</p>
            <div class="sign-space"></div>
            <p class="sign-name">{{ $plan->teacher?->name ?? '...........................' }}</p>
        </div>
    </div>

</div>

</body>
</html>
