<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sesi Mengajar – {{ $lessonSession->topic }}</title>
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
            margin-bottom: 16px;
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
            min-width: 120px;
            font-size: 10pt;
        }
        .info-label::after { content: ':'; }
        .info-value {
            color: #111827;
            font-size: 10pt;
        }

        /* ── Content Section ── */
        .content-section {
            margin-bottom: 18px;
        }
        .content-section h2 {
            font-size: 10pt;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 6px;
        }
        .content-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
            padding: 12px 14px;
            margin-bottom: 10px;
            line-height: 1.6;
            text-align: left;
        }
        .content-box.green { border-left-color: #10b981; }
        .content-box.orange { border-left-color: #f59e0b; }
        .content-box.red { border-left-color: #ef4444; }
        .content-label {
            font-size: 9pt;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .content-value {
            font-size: 10pt;
            color: #1f2937;
            white-space: normal;
            word-wrap: break-word;
            text-align: left !important;
            line-height: 1.5;
            display: block;
        }
        .content-value-pre {
            font-size: 10pt;
            color: #1f2937;
            white-space: pre-line;
            word-wrap: break-word;
            text-align: left !important;
            line-height: 1.5;
            display: block;
        }
        .content-value.empty {
            color: #9ca3af;
            font-style: italic;
        }

        /* ── Achievement Badge ── */
        .achievement-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14pt;
            font-weight: 700;
            text-align: center;
            min-width: 100px;
        }
        .achievement-badge.high { background: #dcfce7; color: #15803d; border-color: #22c55e; }
        .achievement-badge.medium { background: #fef3c7; color: #b45309; border-color: #f59e0b; }
        .achievement-badge.low { background: #fee2e2; color: #b91c1c; border-color: #ef4444; }

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
        .print-info {
            font-size: 8.5pt;
            color: #9ca3af;
            align-self: flex-end;
        }

        /* ── Print styles ── */
        @media print {
            body { background: #fff; padding: 0; }
            .page { box-shadow: none; border-radius: 0; max-width: 100%; padding: 12mm 15mm; }
            .no-print { display: none !important; }
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

        /* ── Content Sections (Explicit Display) ── */
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
            gap: 10px;
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
            margin-bottom: 4px;
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

        /* ── Attendance Summary ── */
        .attendance-summary {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin: 10px 0;
        }
        .attendance-item {
            background: #f9fafb;
            border: 2px solid #dbeafe;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }
        .attendance-count {
            font-size: 18pt;
            font-weight: 700;
            color: #1e40af;
            line-height: 1;
        }
        .attendance-label {
            font-size: 8.5pt;
            color: #6b7280;
            margin-top: 4px;
            text-transform: uppercase;
            font-weight: 600;
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
            <div class="doc-title">LAPORAN SESI MENGAJAR</div>
            <div class="doc-subtitle">
                {{ $lessonSession->session_date->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
    </div>

    {{-- ── Informasi Sesi ── --}}
    <div class="info-section">
        <h2>Informasi Sesi Mengajar</h2>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Guru Pengampu</span>
                <span class="info-value">{{ $lessonSession->teacher?->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mata Pelajaran</span>
                <span class="info-value">{{ $lessonSession->subject?->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span class="info-value">{{ $lessonSession->schoolClass?->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jam Pelajaran</span>
                <span class="info-value">{{ $lessonSession->time_range }}</span>
            </div>
            <div class="info-row" style="grid-column: span 2;">
                <span class="info-label">Topik / Materi</span>
                <span class="info-value">{{ $lessonSession->topic }}</span>
            </div>
        </div>
    </div>

    {{-- ── Rencana Pembelajaran ── --}}
    <div class="content-section">
        <h2>Rencana Pembelajaran</h2>

        <div class="content-box">
            <div class="content-label">📌 Tujuan Pembelajaran</div>
            <div class="content-value">
                @if($objectives->isNotEmpty())
                    @foreach($objectives as $obj)
                        • {{ $obj->name }}<br>
                    @endforeach
                @else
                    <span class="empty">Belum ditentukan</span>
                @endif
            </div>
        </div>

        <div class="content-box green">
            <div class="content-label">⚙️ Metode Pembelajaran</div>
            <div class="content-value">
                @if($methods->isNotEmpty())
                    @foreach($methods as $m)
                        • {{ $m->name }}<br>
                    @endforeach
                @else
                    <span class="empty">Belum ditentukan</span>
                @endif
            </div>
        </div>

        <div class="content-box">
            <div class="content-label">🖥️ Media Pembelajaran</div>
            <div class="content-value">
                @if($mediaItems->isNotEmpty())
                    @foreach($mediaItems as $m)
                        • {{ $m->name }}<br>
                    @endforeach
                @else
                    <span class="empty">Belum ditentukan</span>
                @endif
            </div>
        </div>

        <div class="content-box orange">
            <div class="content-label">✅ Rencana Penilaian</div>
            <div class="content-value-pre">
                @if($lessonSession->assessment_plan)
                    {{ $lessonSession->assessment_plan }}
                @else
                    <span class="empty">Belum ditentukan</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Realisasi Pembelajaran ── --}}
    <div class="content-section">
        <h2>Realisasi Pembelajaran</h2>

        <div style="margin-bottom: 12px;">
            <div class="content-label" style="margin-bottom: 8px;">📊 Pencapaian Pembelajaran</div>
            @if($lessonSession->achievement_percent !== null)
                <div class="achievement-badge {{ $lessonSession->achievement_percent >= 85 ? 'high' : ($lessonSession->achievement_percent >= 75 ? 'medium' : 'low') }}">
                    {{ $lessonSession->achievement_percent }}%
                </div>
            @else
                <span style="color: #9ca3af; font-style: italic;">Belum diisi</span>
            @endif
        </div>

        <div class="content-box">
            <div class="content-label">📝 Catatan Kegiatan</div>
            <div class="content-value-pre">
                @if($lessonSession->execution_notes)
                    {{ $lessonSession->execution_notes }}
                @else
                    <span class="empty">Belum diisi</span>
                @endif
            </div>
        </div>

        <div class="content-box green">
            <div class="content-label">📚 Tugas / PR</div>
            <div class="content-value-pre">
                @if($lessonSession->homework_notes)
                    {{ $lessonSession->homework_notes }}
                @else
                    <span class="empty">Belum diisi</span>
                @endif
            </div>
        </div>

        <div class="content-box red">
            <div class="content-label">⚠️ Kendala</div>
            <div class="content-value-pre">
                @if($lessonSession->issues_notes)
                    {{ $lessonSession->issues_notes }}
                @else
                    <span class="empty">Tidak ada kendala</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Komponen Pembelajaran & Absensi (Explicit Display) ── --}}
    <div class="sections-container">

        {{-- Absensi Section --}}
        <div class="section-group">
            <h2 class="section-title">📊 Ringkasan Absensi Peserta Didik</h2>
            <div class="attendance-summary">
                <div class="attendance-item">
                    <div class="attendance-count">{{ $attendanceSummary['hadir'] ?? 0 }}</div>
                    <div class="attendance-label">Hadir</div>
                </div>
                <div class="attendance-item">
                    <div class="attendance-count">{{ $attendanceSummary['terlambat'] ?? 0 }}</div>
                    <div class="attendance-label">Terlambat</div>
                </div>
                <div class="attendance-item">
                    <div class="attendance-count">{{ $attendanceSummary['sakit'] ?? 0 }}</div>
                    <div class="attendance-label">Sakit</div>
                </div>
                <div class="attendance-item">
                    <div class="attendance-count">{{ $attendanceSummary['izin'] ?? 0 }}</div>
                    <div class="attendance-label">Izin</div>
                </div>
                <div class="attendance-item">
                    <div class="attendance-count">{{ $attendanceSummary['alfa'] ?? 0 }}</div>
                    <div class="attendance-label">Alfa</div>
                </div>
            </div>
        </div>

        {{-- Materials Section --}}
        <div class="section-group">
            <h2 class="section-title">📚 Materi Pembelajaran</h2>
            <div class="section-content">
                @if($lessonSession->materials->isNotEmpty())
                    @foreach($lessonSession->materials as $material)
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
                @if($lessonSession->assignments->isNotEmpty())
                    @foreach($lessonSession->assignments as $assignment)
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
                @if($lessonSession->assessments->isNotEmpty())
                    @foreach($lessonSession->assessments as $assessment)
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
                @if($lessonSession->cases->isNotEmpty())
                    @foreach($lessonSession->cases as $case)
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
            <p class="sign-name">{{ $lessonSession->teacher?->name ?? '...........................' }}</p>
        </div>
    </div>

</div>

</body>
</html>
