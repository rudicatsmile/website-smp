<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rencana Pembelajaran – {{ $plan->title }}</title>
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

        /* ── Info Grid ── */
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

        /* ── Component lists ── */
        .component-section {
            margin-bottom: 18px;
        }
        .component-section h2 {
            font-size: 10pt;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .component-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .component-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #3b82f6;
            border-radius: 6px;
            padding: 10px 14px;
        }
        .component-card.green  { border-left-color: #10b981; }
        .component-card.purple { border-left-color: #8b5cf6; }
        .component-card.orange { border-left-color: #f59e0b; }
        .component-card h3 {
            font-size: 9pt;
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }
        .component-card ul {
            list-style: none;
            padding: 0;
        }
        .component-card ul li {
            font-size: 10pt;
            color: #1f2937;
            padding: 2px 0;
            padding-left: 12px;
            position: relative;
            line-height: 1.4;
        }
        .component-card ul li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #9ca3af;
        }
        .component-card .empty {
            font-size: 9.5pt;
            color: #9ca3af;
            font-style: italic;
        }

        /* ── Topics Table ── */
        .topics-section h2 {
            font-size: 10pt;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        table.topics {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        table.topics thead tr {
            background: #1e3a8a;
            color: #fff;
        }
        table.topics thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9pt;
            border: 1px solid #1e3a8a;
        }
        table.topics tbody tr:nth-child(even) { background: #f0f6ff; }
        table.topics tbody tr:hover { background: #dbeafe; }
        table.topics tbody td {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            line-height: 1.4;
        }
        table.topics td.center {
            text-align: center;
            font-weight: 700;
        }
        table.topics td .badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 1px 8px;
            font-size: 8.5pt;
            font-weight: 600;
            margin: 2px 2px 2px 0;
        }
        table.topics td .badge.green  { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        table.topics td .badge.purple { background: #faf5ff; color: #7e22ce; border-color: #e9d5ff; }
        table.topics td .badge.orange { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        table.topics td .note {
            font-size: 9pt;
            color: #6b7280;
            font-style: italic;
        }

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
            table.topics { page-break-inside: auto; }
            table.topics tr { page-break-inside: avoid; }
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
            <div class="doc-title">RENCANA PEMBELAJARAN</div>
            <div class="doc-subtitle">
                Tahun Ajaran {{ $plan->academic_year }} &bull;
                Semester {{ ucfirst($plan->semester ?? '') }}
            </div>
        </div>
    </div>

    {{-- ── Informasi Umum ── --}}
    <div class="info-section">
        <h2>Informasi Umum</h2>
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
            <div class="info-row">
                <span class="info-label">Semester</span>
                <span class="info-value">{{ ucfirst($plan->semester ?? '') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alokasi Waktu</span>
                <span class="info-value">{{ $plan->time_allocation ?? '—' }}</span>
            </div>
            <div class="info-row" style="grid-column: span 2;">
                <span class="info-label">Topik / Judul</span>
                <span class="info-value">{{ $plan->title }}</span>
            </div>
        </div>
    </div>

    {{-- ── Komponen Pembelajaran ── --}}
    <div class="component-section">
        <h2>Komponen Pembelajaran</h2>
        <div class="component-grid">

            {{-- Tujuan --}}
            <div class="component-card">
                <h3>📌 Tujuan Pembelajaran</h3>
                @if($objectives->isNotEmpty())
                    <ul>
                        @foreach($objectives as $obj)
                            <li>{{ $obj->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="empty">Belum ditentukan</p>
                @endif
            </div>

            {{-- Model --}}
            <div class="component-card green">
                <h3>🧩 Model Pembelajaran</h3>
                @if($models->isNotEmpty())
                    <ul>
                        @foreach($models as $m)
                            <li>{{ $m->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="empty">Belum ditentukan</p>
                @endif
            </div>

            {{-- Metode --}}
            <div class="component-card purple">
                <h3>⚙️ Metode Pembelajaran</h3>
                @if($methods->isNotEmpty())
                    <ul>
                        @foreach($methods as $m)
                            <li>{{ $m->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="empty">Belum ditentukan</p>
                @endif
            </div>

            {{-- Media --}}
            <div class="component-card orange">
                <h3>🖥️ Media Pembelajaran</h3>
                @if(!empty($allMedia))
                    <ul>
                        @foreach($allMedia as $m)
                            <li>{{ $m }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="empty">Belum ditentukan</p>
                @endif
            </div>

        </div>
    </div>

    {{-- ── Daftar Topik per Pertemuan ── --}}
    <div class="topics-section">
        <h2>Rencana Pembelajaran per Pertemuan ({{ $topicsData->count() }} pertemuan)</h2>

        @if($topicsData->isEmpty())
            <p style="color:#9ca3af; font-style:italic; padding: 12px 0;">Belum ada topik yang ditambahkan.</p>
        @else
        <table class="topics">
            <thead>
                <tr>
                    <th style="width:45px;">Minggu</th>
                    <th style="width:55px;">Pertemuan</th>
                    <th style="width:18%;">Topik / Materi</th>
                    <th>Tujuan Pembelajaran</th>
                    <th style="width:18%;">Alur Tujuan Pembelajaran</th>
                    <th style="width:12%;">Metode</th>
                    <th style="width:12%;">Media</th>
                    <th style="width:14%;">Rencana Penilaian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topicsData as $item)
                @php $t = $item['topic']; @endphp
                <tr>
                    <td class="center">{{ $t->week_number }}</td>
                    <td class="center">{{ $t->order }}</td>
                    <td><strong>{{ $t->topic }}</strong>
                        @if($t->default_duration_minutes)
                            <br><small style="color:#6b7280;">{{ $t->default_duration_minutes }} menit</small>
                        @endif
                    </td>
                    <td>
                        @forelse($item['objectives'] as $obj)
                            <span class="badge">{{ $obj }}</span>
                        @empty
                            <span style="color:#9ca3af;">—</span>
                        @endforelse
                    </td>
                    <td>
                        @if(!empty($item['learning_paths']))
                            @foreach($item['learning_paths'] as $path)
                                <div style="margin-bottom:4px;">
                                    <span style="font-size:9pt;">{{ $path['description'] }}</span>
                                    <span class="badge green">{{ $path['kko_level'] }}</span>
                                </div>
                            @endforeach
                        @else
                            <span style="color:#9ca3af;">—</span>
                        @endif
                    </td>
                    <td>
                        @forelse($item['methods'] as $m)
                            <span class="badge purple">{{ $m }}</span>
                        @empty
                            <span style="color:#9ca3af;">—</span>
                        @endforelse
                    </td>
                    <td>
                        @forelse($item['media'] as $m)
                            <span class="badge orange">{{ $m }}</span>
                        @empty
                            <span style="color:#9ca3af;">—</span>
                        @endforelse
                    </td>
                    <td>
                        @if($t->assessment_plan)
                            {{ $t->assessment_plan }}
                        @else
                            <span class="note">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
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
