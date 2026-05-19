<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 8pt; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #1a5276; padding-bottom: 6px; }
        .header h1 { font-size: 13pt; font-weight: bold; color: #1a5276; }
        .header h2 { font-size: 10pt; font-weight: bold; margin-top: 2px; }
        .header p  { font-size: 8pt; color: #555; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 7pt; }
        th, td { border: 0.5pt solid #ccc; padding: 3px 4px; }
        thead th { background: #1a5276; color: #fff; text-align: center; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        .col-name { text-align: left; min-width: 90px; }
        .col-nis  { text-align: center; }
        .col-no   { text-align: center; width: 20px; }
        .col-date { text-align: center; width: 22px; }
        .col-sum  { text-align: center; font-weight: bold; width: 24px; }
        .s-hadir     { background: #d4efdf; color: #1e8449; }
        .s-sakit     { background: #fef9e7; color: #b7950b; }
        .s-izin      { background: #d6eaf8; color: #1a6fa0; }
        .s-alpa      { background: #fde8e8; color: #c0392b; }
        .s-terlambat { background: #f0f0f0; color: #555; }
        .sum-s    { background: #fef3cd; color: #856404; }
        .sum-i    { background: #cce5ff; color: #004085; }
        .sum-a    { background: #f8d7da; color: #721c24; }
        .sum-h    { background: #d1ecf1; color: #0c5460; }
        .persen-ok   { background: #d4edda; color: #155724; font-weight: bold; }
        .persen-bad  { background: #f8d7da; color: #721c24; font-weight: bold; }
        .weekend  { background: #eeeeee !important; }
        .footer { margin-top: 12px; font-size: 7pt; color: #777; text-align: right; }
        .legend { margin-top: 8px; font-size: 7pt; color: #555; }
        .legend span { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ABSENSI SISWA</h1>
        <h2>{{ $class?->name ?? '' }}</h2>
        <p>
            Periode: {{ \Carbon\Carbon::parse($dateFrom)->isoFormat('D MMMM Y') }}
            &ndash; {{ \Carbon\Carbon::parse($dateTo)->isoFormat('D MMMM Y') }}
            &nbsp;&middot;&nbsp; {{ count($dates) }} hari &nbsp;&middot;&nbsp; {{ count($rows) }} siswa
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-name">Nama</th>
                <th class="col-nis">NIS</th>
                @foreach($dates as $date)
                    @php $d = \Carbon\Carbon::parse($date); @endphp
                    <th class="col-date {{ in_array($d->dayOfWeek, [0,6]) ? 'weekend' : '' }}">
                        {{ $d->format('d') }}<br><span style="font-weight:normal;font-size:6pt">{{ $d->isoFormat('dd') }}</span>
                    </th>
                @endforeach
                <th class="col-sum sum-s">S</th>
                <th class="col-sum sum-i">I</th>
                <th class="col-sum sum-a">A</th>
                <th class="col-sum sum-h">Hadir</th>
                <th class="col-sum">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="col-no">{{ $row['no'] }}</td>
                    <td class="col-name">{{ $row['student']->name }}</td>
                    <td class="col-nis" style="font-family:monospace">{{ $row['student']->nis ?? '—' }}</td>
                    @foreach($dates as $date)
                        @php
                            $rec    = $row['daily']->get($date);
                            $status = $rec?->status;
                            $d      = \Carbon\Carbon::parse($date);
                            $isWeekend = in_array($d->dayOfWeek, [0, 6]);
                            $labels = ['hadir'=>'H','sakit'=>'S','izin'=>'I','alpa'=>'A','terlambat'=>'T'];
                        @endphp
                        <td class="col-date {{ $isWeekend ? 'weekend' : '' }} {{ $status ? 's-'.$status : '' }}">
                            {{ $status ? ($labels[$status] ?? $status) : '' }}
                        </td>
                    @endforeach
                    <td class="col-sum sum-s">{{ $row['sakit'] ?: '—' }}</td>
                    <td class="col-sum sum-i">{{ $row['izin'] ?: '—' }}</td>
                    <td class="col-sum sum-a">{{ $row['alpa'] ?: '—' }}</td>
                    <td class="col-sum sum-h">{{ $row['hadir'] }}</td>
                    <td class="col-sum {{ $row['persen'] >= 75 ? 'persen-ok' : 'persen-bad' }}">{{ $row['persen'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <span><strong>H</strong>=Hadir</span>
        <span><strong>S</strong>=Sakit</span>
        <span><strong>I</strong>=Izin</span>
        <span><strong>A</strong>=Alpa</span>
        <span><strong>T</strong>=Terlambat (dihitung Hadir)</span>
        <span style="color:#c0392b">% merah = kehadiran &lt; 75%</span>
    </div>

    <div class="footer">
        Dicetak: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB
    </div>
</body>
</html>
