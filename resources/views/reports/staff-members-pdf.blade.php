<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Guru dan Mata Pelajaran</title>
    <style>
        @page { margin: 2cm; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 10pt;
            line-height: 1.5;
        }
        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .kop-surat table {
            width: 100%;
            border: none;
        }
        .kop-surat td {
            vertical-align: middle;
            border: none;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .sekolah-name {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .sekolah-address {
            font-size: 10pt;
            margin: 5px 0 0 0;
        }
        /* Title */
        .report-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        /* Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th {
            background-color: #1e3a8a; /* Tailwind blue-900 */
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border: 1px solid #1e3a8a;
        }
        table.data-table td {
            padding: 10px;
            border: 1px solid #d1d5db; /* Tailwind gray-300 */
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f3f4f6; /* Tailwind gray-100 */
        }
        /* Badges */
        .badge {
            display: inline-block;
            background-color: #dbeafe; /* Tailwind blue-100 */
            color: #1e40af; /* Tailwind blue-800 */
            border: 1px solid #93c5fd;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: 600;
            margin: 2px 2px 2px 0;
        }
        /* Footer / Tanda Tangan */
        .footer-sig {
            width: 100%;
            margin-top: 40px;
        }
        .footer-sig table {
            width: 100%;
            border: none;
        }
        .footer-sig td {
            width: 50%;
            border: none;
            text-align: center;
        }
        .sig-box {
            display: inline-block;
            text-align: left;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .sig-nip {
            margin-top: 2px;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/logo-smp.png');
        $logoData = '';
        if (file_exists($logoPath)) {
            $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="kop-surat">
        <table>
            <tr>
                <td style="width: 15%; text-align: right; padding-right: 15px;">
                    @if($logoData)
                        <img src="{{ $logoData }}" class="logo" alt="Logo Sekolah">
                    @endif
                </td>
                <td style="width: 85%; text-align: center;">
                    <h1 class="sekolah-name">{{ $settings->school_name ?: 'SMP AL-WATHONIYAH 9' }}</h1>
                    <p class="sekolah-address">
                        {{ $settings->address ?: 'Jl. Raya Bekasi Timur KM 17, Klender, Duren Sawit, Jakarta Timur' }}<br>
                        Telp: {{ $settings->phone ?: '(021) 1234567' }} | Email: {{ $settings->email ?: 'info@alwathoniyah9.org' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title">
        Daftar Guru dan Mata Pelajaran
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Nama Guru</th>
                <th style="width: 15%;">NIP</th>
                <th style="width: 15%;">No. Telepon</th>
                <th style="width: 40%;">Mata Pelajaran yang Diampu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staffMembers as $index => $staff)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $staff->name }}</strong></td>
                    <td>{{ $staff->nip ?: '-' }}</td>
                    <td>{{ $staff->phone ?: '-' }}</td>
                    <td>
                        @if($staff->teachingSubjects && $staff->teachingSubjects->count() > 0)
                            @foreach($staff->teachingSubjects as $subject)
                                <span class="badge">{{ $subject->name }}</span>
                            @endforeach
                        @else
                            <span style="color: #6b7280; font-style: italic;">Belum ada mapel</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data guru pelajaran aktif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-sig">
        <table>
            <tr>
                <td></td>
                <td>
                    <div class="sig-box">
                        <p>Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Kepala Sekolah,</p>
                        <br><br><br><br>
                        <p class="sig-name">....................................................</p>
                        <p class="sig-nip">NIP. ..........................................</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
