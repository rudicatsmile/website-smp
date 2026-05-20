<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penilaian Peserta Didik</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; color: #1e293b; background: white; }
        .page { padding: 16px 20px; }

        /* Header */
        .header { text-align: center; margin-bottom: 14px; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; }
        .header h1 { font-size: 14px; font-weight: 800; color: #1e3a8a; letter-spacing: 0.05em; margin-bottom: 3px; }
        .header p { font-size: 9px; color: #64748b; }
        .meta { display: flex; justify-content: center; gap: 20px; margin-top: 6px; flex-wrap: wrap; font-size: 9px; color: #374151; }
        .meta span { font-weight: 700; }

        /* Table */
        table { width: 100%; border-collapse: collapse; font-size: 8px; margin-top: 10px; }
        th, td { border: 1px solid #d1d5db; padding: 5px 5px; text-align: center; }
        td.name-cell { text-align: left; padding-left: 6px; white-space: nowrap; }

        /* Group headers */
        .th-base   { background: #1e3a8a; color: white; font-weight: 700; font-size: 8px; }
        .th-tp     { background: #1d4ed8; color: white; font-weight: 700; font-size: 7.5px; }
        .th-exam   { background: #ea580c; color: white; font-weight: 700; font-size: 7.5px; }
        .th-akhir  { background: #16a34a; color: white; font-weight: 700; font-size: 7.5px; }

        /* Data cells */
        .val-tp    { background: #eff6ff; color: #1e40af; font-weight: 600; }
        .val-exam  { background: #fff7ed; color: #9a3412; font-weight: 600; }
        .val-akhir { background: #f0fdf4; color: #166534; font-weight: 700; }
        .val-empty { color: #cbd5e1; }

        /* Row alternation */
        tr.even { background: #f8fafc; }
        tr.odd  { background: white; }

        /* Average row */
        tr.avg-row td { background: #f0fdf4; font-weight: 800; font-size: 8px; color: #166534; border-top: 2px solid #22c55e; }
        tr.avg-row td.avg-label { text-align: right; color: #475569; background: #f8fafc; }

        .footer { margin-top: 12px; font-size: 7.5px; color: #94a3b8; text-align: right; }
        .legend { margin-top: 6px; display: flex; gap: 12px; font-size: 7.5px; }
        .legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 2px; margin-right: 3px; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <h1>LAPORAN PENILAIAN PESERTA DIDIK</h1>
        <div class="meta">
            <span>Kelas: {{ $class->name }}</span>
            &middot;
            <span>Mata Pelajaran: {{ $subject->name }}</span>
            &middot;
            <span>Tahun Ajaran: {{ $academicYear }}</span>
            &middot;
            <span>Jumlah Siswa: {{ count($rows) }}</span>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            {{-- Group row --}}
            <tr>
                <th rowspan="2" class="th-base">No</th>
                <th rowspan="2" class="th-base">NIS</th>
                <th rowspan="2" class="th-base" style="text-align:left; padding-left:6px;">Nama Peserta Didik</th>

                @if($tpSessions->count() > 0)
                    <th colspan="{{ $tpSessions->count() }}" class="th-tp">Tujuan Pembelajaran (TP)</th>
                @endif

                @if($presentExamTypes->count() > 0)
                    <th colspan="{{ $presentExamTypes->count() }}" class="th-exam">Sumatif Lingkup Materi</th>
                @endif

                <th colspan="2" class="th-akhir">Sumatif Akhir</th>
            </tr>

            {{-- Sub-header row --}}
            <tr>
                @foreach($tpSessions as $idx => $session)
                    <th class="th-tp" style="font-size:7px;">
                        TP-{{ $idx + 1 }}<br>
                        <span style="font-weight:400; opacity:0.85;">{{ \Carbon\Carbon::parse($session->session_date)->format('d/m') }}</span>
                    </th>
                @endforeach

                @foreach($presentExamTypes as $type)
                    <th class="th-exam">{{ $typeLabels[$type] ?? strtoupper($type) }}</th>
                @endforeach

                <th class="th-akhir">Sem 1</th>
                <th class="th-akhir">Sem 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr class="{{ $loop->odd ? 'odd' : 'even' }}">
                    <td>{{ $row['no'] }}</td>
                    <td style="font-size:7.5px; font-family:monospace;">{{ $row['student']->nis ?? '—' }}</td>
                    <td class="name-cell">{{ $row['student']->name }}</td>

                    @foreach($tpSessions as $session)
                        @php $val = $row['tpScores'][$session->id] ?? null; @endphp
                        <td class="{{ $val !== null ? 'val-tp' : 'val-empty' }}">
                            {{ $val !== null ? number_format($val, 1) : '—' }}
                        </td>
                    @endforeach

                    @foreach($presentExamTypes as $type)
                        @php $val = $row['examScoresByType'][$type] ?? null; @endphp
                        <td class="{{ $val !== null ? 'val-exam' : 'val-empty' }}">
                            {{ $val !== null ? number_format($val, 1) : '—' }}
                        </td>
                    @endforeach

                    @php $s1 = $row['sem1']; $s2 = $row['sem2']; @endphp
                    <td class="{{ $s1 !== null ? 'val-akhir' : 'val-empty' }}">
                        {{ $s1 !== null ? number_format($s1, 1) : '—' }}
                    </td>
                    <td class="{{ $s2 !== null ? 'val-akhir' : 'val-empty' }}">
                        {{ $s2 !== null ? number_format($s2, 1) : '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="avg-row">
                <td colspan="3" class="avg-label">Rata-rata Kelas</td>

                @foreach($tpSessions as $session)
                    @php
                        $vals = collect($rows)->pluck("tpScores.{$session->id}")->filter(fn ($v) => $v !== null);
                        $avg  = $vals->isNotEmpty() ? round($vals->avg(), 1) : null;
                    @endphp
                    <td>{{ $avg !== null ? number_format($avg, 1) : '—' }}</td>
                @endforeach

                @foreach($presentExamTypes as $type)
                    @php
                        $vals = collect($rows)->pluck("examScoresByType.{$type}")->filter(fn ($v) => $v !== null);
                        $avg  = $vals->isNotEmpty() ? round($vals->avg(), 1) : null;
                    @endphp
                    <td>{{ $avg !== null ? number_format($avg, 1) : '—' }}</td>
                @endforeach

                @php
                    $s1avg = collect($rows)->pluck('sem1')->filter(fn ($v) => $v !== null);
                    $s2avg = collect($rows)->pluck('sem2')->filter(fn ($v) => $v !== null);
                @endphp
                <td>{{ $s1avg->isNotEmpty() ? number_format($s1avg->avg(), 1) : '—' }}</td>
                <td>{{ $s2avg->isNotEmpty() ? number_format($s2avg->avg(), 1) : '—' }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak: {{ now()->isoFormat('dddd, D MMMM Y, HH:mm') }} &middot;
        Nilai TP = rata-rata penilaian di sesi &middot; Sumatif LM = rata-rata nilai ujian per jenis &middot; Sumatif Akhir = rata-rata semua penilaian per semester
    </div>
</div>
</body>
</html>
