<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jurnal Mengajar</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; }
        .meta-table { width: 100%; margin-bottom: 15px; }
        .meta-table td { padding: 3px; }
        .main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .main-table th, .main-table td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        .main-table th { background-color: #f0f0f0; text-align: center; }
        .footer-table { width: 100%; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">JURNAL MENGAJAR GURU</div>
        <div class="subtitle">Bulan: {{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }}</div>
    </div>

    <table class="meta-table">
        <tr>
            <td width="15%"><strong>Nama Guru</strong></td>
            <td width="35%">: {{ $teacher->name ?? '-' }}</td>
            <td width="15%"><strong>NIP / NIK</strong></td>
            <td width="35%">: {{ $teacher->nip ?? '-' }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Hari / Tanggal</th>
                <th width="10%">Waktu</th>
                <th width="15%">Kelas & Mapel</th>
                <th width="20%">Topik / Materi</th>
                <th width="7%">Capaian</th>
                <th width="30%">Catatan & Kendala</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $index => $session)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d F Y') }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}<br>
                    <small>{{ $session->period }}</small>
                </td>
                <td>
                    <strong>{{ $session->schoolClass->name ?? '-' }}</strong><br>
                    {{ $session->subject->name ?? '-' }}
                </td>
                <td>{{ $session->topic }}</td>
                <td style="text-align: center">{{ $session->achievement_percent }}%</td>
                <td>
                    @if($session->execution_notes)
                        <strong>Kegiatan:</strong> {{ $session->execution_notes }}<br>
                    @endif
                    @if($session->issues_notes)
                        <strong>Kendala:</strong> {{ $session->issues_notes }}<br>
                    @endif
                    @if($session->homework_notes)
                        <strong>Tugas:</strong> {{ $session->homework_notes }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center">Tidak ada data sesi mengajar yang selesai pada bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td width="50%">
                Mengetahui,<br>
                Kepala Sekolah
                <br><br><br><br><br>
                ( ......................................... )<br>
                NIP.
            </td>
            <td width="50%">
                Guru Mata Pelajaran
                <br><br><br><br><br>
                <strong>{{ $teacher->name ?? '( ......................................... )' }}</strong><br>
                NIP. {{ $teacher->nip ?? '...................' }}
            </td>
        </tr>
    </table>
</body>
</html>
