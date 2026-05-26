<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tahfidz – {{ $participant->student->name }}</title>
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
            position: relative;
            overflow: hidden;
        }

        /* Decorative top bar */
        .page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4338ca, #6366f1, #818cf8);
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 16px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .header img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }
        .header-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4338ca, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: 800;
        }
        .header-text { flex: 1; }
        .school-name {
            font-size: 15pt;
            font-weight: 700;
            color: #1e3a8a;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: 700;
            color: #4338ca;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-subtitle {
            font-size: 9pt;
            color: #6b7280;
            margin-top: 3px;
        }

        /* ── Student Info Card ── */
        .student-card {
            background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
            border: 1px solid #c7d2fe;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 20px;
        }
        .student-card h3 {
            font-size: 9pt;
            font-weight: 700;
            color: #4338ca;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .info-label {
            font-size: 8.5pt;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .info-value {
            font-size: 10.5pt;
            font-weight: 700;
            color: #1f2937;
        }

        /* ── Progress Section ── */
        .progress-section {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .progress-card {
            flex: 1;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            text-align: center;
        }
        .progress-card.highlight {
            background: linear-gradient(135deg, #4338ca, #6366f1);
            border-color: transparent;
            color: white;
        }
        .progress-card .pc-label {
            font-size: 8.5pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            opacity: 0.8;
        }
        .progress-card .pc-value {
            font-size: 18pt;
            font-weight: 800;
            margin-top: 4px;
        }
        .progress-card.highlight .pc-label { color: rgba(255,255,255,0.85); }
        .progress-card.highlight .pc-value { color: white; }

        /* ── Progress Bar ── */
        .progress-bar-ctn {
            margin-bottom: 20px;
        }
        .progress-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 6px;
        }
        .progress-bar {
            height: 10px;
            background: #e5e7eb;
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #4338ca, #6366f1, #818cf8);
            transition: width 0.3s;
        }

        /* ── Table ── */
        .table-section {
            margin-bottom: 20px;
        }
        .table-section h3 {
            font-size: 10pt;
            font-weight: 700;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e5e7eb;
        }
        table.grades {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        table.grades thead tr {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            color: white;
        }
        table.grades thead th {
            padding: 9px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table.grades thead th:first-child { border-radius: 8px 0 0 0; }
        table.grades thead th:last-child { border-radius: 0 8px 0 0; }
        table.grades tbody tr:nth-child(even) { background: #f8fafc; }
        table.grades tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        table.grades td.center { text-align: center; }
        .score-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: 700;
        }
        .score-high { background: #dcfce7; color: #166534; }
        .score-mid { background: #fef9c3; color: #854d0e; }
        .score-low { background: #fee2e2; color: #991b1b; }

        /* ── Footer ── */
        .footer {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
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
        .print-date {
            font-size: 8.5pt;
            color: #9ca3af;
        }

        /* ── Motivational Quote ── */
        .quote-box {
            background: linear-gradient(135deg, #faf5ff, #ede9fe);
            border: 1px solid #ddd6fe;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            text-align: center;
        }
        .quote-box .quote-text {
            font-size: 11pt;
            font-style: italic;
            color: #5b21b6;
            font-weight: 500;
        }
        .quote-box .quote-source {
            font-size: 8.5pt;
            color: #7c3aed;
            margin-top: 4px;
            font-weight: 600;
        }

        /* ── Print ── */
        @media print {
            body { background: #fff; padding: 0; }
            .page { box-shadow: none; border-radius: 0; max-width: 100%; padding: 12mm 15mm; }
            .no-print { display: none !important; }
            table.grades { page-break-inside: auto; }
            table.grades tr { page-break-inside: avoid; }
        }

        /* ── Toolbar ── */
        .toolbar {
            max-width: 210mm;
            margin: 0 auto 16px;
            display: flex;
            gap: 10px;
        }
        .btn-print {
            background: linear-gradient(135deg, #4338ca, #6366f1);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 11pt;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print:hover { opacity: 0.9; }
        .btn-close {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 11pt;
            cursor: pointer;
        }
        .btn-close:hover { background: #e5e7eb; }
    </style>
</head>
<body>

<div class="toolbar no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
</div>

<div class="page">

    {{-- Header --}}
    <div class="header">
        @if($schoolLogo)
            <img src="{{ asset('storage/' . $schoolLogo) }}" alt="{{ $schoolName }}">
        @else
            <div class="header-icon">📖</div>
        @endif
        <div class="header-text">
            <div class="school-name">{{ $schoolName }}</div>
            <div class="doc-title">Laporan Hafalan Al-Qur'an</div>
            <div class="doc-subtitle">Program Sahabat Qur'an • Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</div>
        </div>
    </div>

    {{-- Student Info --}}
    <div class="student-card">
        <h3>📋 Data Peserta</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">{{ $participant->student->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NIS</span>
                <span class="info-value">{{ $participant->student->nis ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kelas Sekolah</span>
                <span class="info-value">{{ $participant->student->schoolClass?->name ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Kelas Tahfidz</span>
                <span class="info-value">{{ $participant->tahfidzClass?->name ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Daftar</span>
                <span class="info-value">{{ $participant->enrolled_at?->isoFormat('D MMMM Y') ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value" style="color:{{ $participant->is_active ? '#059669' : '#dc2626' }};">{{ $participant->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
            </div>
        </div>
    </div>

    {{-- Progress Cards --}}
    @php
        $totalGrades = $grades->count();
        $target = $participant->surah_target;
        $progress = $target > 0 ? round(($totalGrades / $target) * 100, 1) : 0;
        $avgScore = $totalGrades > 0 ? round($grades->avg('score'), 1) : 0;
    @endphp

    <div class="progress-section">
        <div class="progress-card highlight">
            <div class="pc-label">Progres Hafalan</div>
            <div class="pc-value">{{ $progress }}%</div>
        </div>
        <div class="progress-card">
            <div class="pc-label">Surat Selesai</div>
            <div class="pc-value" style="color:#4338ca;">{{ $totalGrades }} / {{ $target }}</div>
        </div>
        <div class="progress-card">
            <div class="pc-label">Nilai Rata-Rata</div>
            <div class="pc-value" style="color:{{ $avgScore >= 80 ? '#059669' : ($avgScore >= 60 ? '#d97706' : '#dc2626') }};">{{ $avgScore }}</div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="progress-bar-ctn">
        <div class="progress-bar-label">
            <span>Pencapaian Hafalan</span>
            <span>{{ $totalGrades }} dari {{ $target }} surat</span>
        </div>
        <div class="progress-bar">
            <div class="progress-bar-fill" style="width: {{ min($progress, 100) }}%;"></div>
        </div>
    </div>

    {{-- Motivational Quote --}}
    <div class="quote-box">
        <div class="quote-text">"Sebaik-baik kalian adalah yang mempelajari Al-Qur'an dan mengajarkannya."</div>
        <div class="quote-source">— HR. Bukhari</div>
    </div>

    {{-- Grades Table --}}
    <div class="table-section">
        <h3>📊 Rincian Hafalan ({{ $totalGrades }} Surat)</h3>

        @if($grades->isEmpty())
            <p style="color:#9ca3af;font-style:italic;padding:16px 0;">Belum ada surat yang diselesaikan.</p>
        @else
            <table class="grades">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Nama Surat</th>
                        <th style="width:80px;text-align:center;">Nilai</th>
                        <th>Guru Penguji</th>
                        <th>Catatan</th>
                        <th style="width:95px;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $i => $grade)
                        <tr>
                            <td class="center">{{ $i + 1 }}</td>
                            <td style="font-weight:600;">{{ $grade->surah }}</td>
                            <td class="center">
                                @php
                                    $scoreClass = $grade->score >= 80 ? 'score-high' : ($grade->score >= 60 ? 'score-mid' : 'score-low');
                                @endphp
                                <span class="score-badge {{ $scoreClass }}">{{ $grade->score }}</span>
                            </td>
                            <td>{{ $grade->teacher?->name ?? '—' }}</td>
                            <td style="font-size:9pt;color:#6b7280;">{{ $grade->description ?? '—' }}</td>
                            <td style="font-size:9pt;">{{ $grade->created_at?->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>
            <p class="print-date">Dicetak: {{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            <p class="print-date" style="margin-top:2px;">{{ $schoolName }}</p>
        </div>
        <div class="signature-box">
            <p class="sign-label">Koordinator Tahfidz,</p>
            <div class="sign-space"></div>
            <p class="sign-name">.............................</p>
        </div>
    </div>

</div>

</body>
</html>
