<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jurnal Mengajar Pendidik</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 8pt; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #0f4c81; padding-bottom: 8px; }
        .header h1 { font-size: 13pt; font-weight: bold; color: #0f4c81; }
        .header h2 { font-size: 9pt; font-weight: bold; margin-top: 3px; color: #1e293b; }
        .header p  { font-size: 7.5pt; color: #64748b; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 7.5pt; }
        thead th {
            background: #0f4c81;
            color: #fff;
            text-align: center;
            font-weight: bold;
            padding: 5px 4px;
            border: 0.5pt solid #0a3a6b;
            vertical-align: middle;
        }
        tbody td { border: 0.5pt solid #c9d9e8; padding: 5px 4px; vertical-align: top; }
        tbody tr:nth-child(even) { background: #f0f6fb; }
        tfoot td { border: 0.5pt solid #c9d9e8; padding: 5px 4px; font-weight: bold; }
        .col-no     { width: 20px; text-align: center; }
        .col-date   { width: 110px; }
        .col-week   { width: 45px; text-align: center; }
        .col-materi { width: auto; }
        .col-hadir  { width: 55px; text-align: center; }
        .col-ket    { width: 110px; }
        .date-main  { font-weight: bold; color: #0f4c81; }
        .date-sub   { font-size: 6.5pt; color: #64748b; margin-top: 2px; }
        .week-badge { display: inline-block; width: 26px; height: 26px; border-radius: 50%; background: #dbeafe; color: #1d4ed8; font-weight: bold; font-size: 11pt; text-align: center; line-height: 26px; }
        .hadir-badge { display: inline-block; padding: 2px 8px; border-radius: 12px; background: #d1fae5; color: #065f46; font-weight: bold; font-size: 9pt; }
        .hadir-zero  { display: inline-block; padding: 2px 8px; border-radius: 12px; background: #f1f5f9; color: #94a3b8; font-weight: bold; font-size: 9pt; }
        .topic      { font-weight: bold; color: #1e293b; }
        .obj        { font-size: 6.5pt; color: #64748b; margin-top: 2px; line-height: 1.35; }
        .footer     { margin-top: 14px; font-size: 7pt; color: #94a3b8; text-align: right; }
        .total-row  { background: #dbeafe !important; }
    </style>
</head>
<body>
    <div class="header">
        <h1>JURNAL MENGAJAR PENDIDIK</h1>
        <h2>
            @if($class) Kelas {{ $class->name }} @endif
            @if($subject) &mdash; {{ $subject->name }} @endif
            @if($academicYear) &mdash; Tahun Pelajaran {{ $academicYear }} @endif
        </h2>
        <p>
            Periode: {{ \Carbon\Carbon::parse($dateFrom)->isoFormat('D MMMM Y') }}
            &ndash; {{ \Carbon\Carbon::parse($dateTo)->isoFormat('D MMMM Y') }}
            &nbsp;&middot;&nbsp; {{ count($rows) }} sesi
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-date">Hari &amp; Tanggal</th>
                <th class="col-week">Minggu<br>Pertemuan<br>Ke</th>
                <th class="col-materi">Bahasan Materi</th>
                <th class="col-hadir">Jumlah<br>Siswa<br>Hadir</th>
                <th class="col-ket">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="col-no" style="font-size:7pt; color:#94a3b8;">{{ $row['no'] }}</td>
                    <td class="col-date">
                        <div class="date-main">{{ $row['date_label'] }}</div>
                        <div class="date-sub">
                            {{ $row['class_name'] }}
                            @if($row['session']->start_time)
                                &middot; {{ substr($row['session']->start_time, 0, 5) }}–{{ substr($row['session']->end_time ?? '', 0, 5) }}
                            @endif
                            <br>{{ $row['subject_name'] }}
                        </div>
                    </td>
                    <td class="col-week">
                        @if($row['week_number'])
                            <span class="week-badge">{{ $row['week_number'] }}</span>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                    <td class="col-materi">
                        <div class="topic">{{ $row['topic'] }}</div>
                        @if($row['session']->learning_objectives)
                            <div class="obj">{{ \Illuminate\Support\Str::limit($row['session']->learning_objectives, 120) }}</div>
                        @endif
                    </td>
                    <td class="col-hadir">
                        @if($row['hadir'] > 0)
                            <span class="hadir-badge">{{ $row['hadir'] }}</span>
                        @else
                            <span class="hadir-zero">0</span>
                        @endif
                    </td>
                    <td class="col-ket">{{ $row['notes'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align:right; color:#1e40af; font-size:8pt;">TOTAL KEHADIRAN</td>
                <td class="col-hadir" style="text-align:center; color:#065f46; font-size:10pt;">{{ collect($rows)->sum('hadir') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB
    </div>
</body>
</html>
