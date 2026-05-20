<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catatan Kasus Peserta Didik</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; color: #1e293b; background: white; }
        .page { padding: 16px 20px; }

        .header { text-align: center; margin-bottom: 12px; border-bottom: 2px solid #7c2d12; padding-bottom: 10px; }
        .header h1 { font-size: 13px; font-weight: 800; color: #7c2d12; letter-spacing: 0.04em; margin-bottom: 2px; }
        .header h2 { font-size: 10px; font-weight: 600; color: #64748b; margin-bottom: 4px; }
        .meta { display: flex; justify-content: center; gap: 16px; font-size: 8.5px; color: #374151; flex-wrap: wrap; margin-top: 4px; }
        .meta span { font-weight: 700; }

        table { width: 100%; border-collapse: collapse; font-size: 8px; margin-top: 10px; }
        th, td { border: 1px solid #d1d5db; padding: 5px 6px; }
        th { background: #7c2d12; color: white; font-weight: 700; text-align: center; font-size: 8px; }
        td.num  { text-align: center; color: #94a3b8; }
        td.center { text-align: center; }
        td.name { font-weight: 600; }
        td.s-col  { text-align: center; font-weight: 800; color: #166534; font-size: 10px; }
        td.ts-col { text-align: center; font-weight: 800; color: #dc2626; font-size: 10px; }
        tr.odd  { background: white; }
        tr.even { background: #fafafa; }

        .legend { margin-top: 8px; font-size: 7.5px; color: #64748b; }
        .footer { margin-top: 10px; font-size: 7.5px; color: #94a3b8; text-align: right; }
        .summary { display: flex; gap: 16px; margin-top: 6px; font-size: 8px; }
        .chip { padding: 2px 10px; border-radius: 999px; font-weight: 700; }
        .chip-total { background: #fed7aa; color: #7c2d12; }
        .chip-s  { background: #dcfce7; color: #166534; }
        .chip-ts { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <h1>CATATAN KASUS PESERTA DIDIK SELAMA KEGIATAN BELAJAR</h1>
        <div class="meta">
            <span>Kelas: {{ $class->name }}</span>
            &middot;
            <span>Mata Pelajaran: {{ $subject->name }}</span>
            &middot;
            <span>Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</span>
        </div>
        <div class="summary">
            <span class="chip chip-total">Total: {{ count($rows) }} kasus</span>
            <span class="chip chip-s">Selesai (S): {{ collect($rows)->where('selesai', true)->count() }}</span>
            <span class="chip chip-ts">Tidak Selesai (TS): {{ collect($rows)->where('selesai', false)->count() }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th style="min-width:120px; text-align:left; padding-left:6px;">Nama Peserta Didik</th>
                <th style="width:65px;">Tanggal</th>
                <th style="width:55px;">Kelas</th>
                <th style="min-width:180px; text-align:left; padding-left:6px;">Masalah / Kasus</th>
                <th style="width:24px;">S</th>
                <th style="width:24px;">TS</th>
                <th style="min-width:140px; text-align:left; padding-left:6px;">Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr class="{{ $loop->odd ? 'odd' : 'even' }}">
                    <td class="num">{{ $row['no'] }}</td>
                    <td class="name" style="padding-left:6px;">{{ $row['student']?->name ?? '—' }}</td>
                    <td class="center">{{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->format('d/m/Y') : '—' }}</td>
                    <td class="center">{{ $row['class'] }}</td>
                    <td style="padding-left:6px;">{{ $row['problem'] }}</td>
                    <td class="s-col">{{ $row['selesai'] ? '✓' : '' }}</td>
                    <td class="ts-col">{{ ! $row['selesai'] ? '✓' : '' }}</td>
                    <td style="padding-left:6px; color:#475569;">{{ $row['follow_up'] ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend"><strong>S</strong> = Selesai &nbsp;&nbsp; <strong>TS</strong> = Tidak Selesai</div>
    <div class="footer">Dicetak: {{ now()->isoFormat('dddd, D MMMM Y, HH:mm') }}</div>
</div>
</body>
</html>
