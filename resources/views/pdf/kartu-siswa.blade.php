<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pelajar</title>
    <style>
        @page { size: A4; margin: 10mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 9px; }

        .grid-wrap { width: 100%; }

        /* CR80 size: 85.6mm x 54mm */
        .card {
            display: inline-block;
            width: 85.6mm;
            height: 54mm;
            margin: 0 1mm 3mm 0;
            vertical-align: top;
            position: relative;
            page-break-inside: avoid;
            border-radius: 4mm;
            overflow: hidden;
            background: #1e3a8a;
            color: #ffffff;
        }

        /* Background gradient simulation via layered solid bg */
        .bg-layer-1 {
            position: absolute; inset: 0;
            background: #1e40af;
        }
        .bg-layer-2 {
            position: absolute; top: 0; right: 0; width: 60%; height: 100%;
            background: #0891b2;
            opacity: 0.65;
        }
        .bg-layer-3 {
            position: absolute; bottom: 0; left: 0; right: 0; height: 1.5mm;
            background: #f59e0b;
        }
        .bg-blob {
            position: absolute; right: -10mm; top: -10mm;
            width: 30mm; height: 30mm;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .inner { position: relative; padding: 3mm 3.5mm; height: 100%; }

        /* HEADER */
        .header {
            border-bottom: 0.3mm solid rgba(255,255,255,0.3);
            padding-bottom: 1.5mm;
            margin-bottom: 2mm;
            height: 9mm;
            position: relative;
        }
        .header .logo {
            position: absolute; left: 0; top: 0;
            width: 7.5mm; height: 7.5mm;
            background: #ffffff;
            border-radius: 1mm;
            padding: 0.5mm;
        }
        .header .logo img { width: 100%; height: 100%; }
        .header .head-text {
            margin-left: 9mm;
            padding-top: 0.3mm;
        }
        .head-eyebrow {
            font-size: 6px;
            text-transform: uppercase;
            letter-spacing: 0.6mm;
            color: rgba(255,255,255,0.75);
            line-height: 1;
            margin-bottom: 0.7mm;
        }
        .head-school {
            font-size: 9px;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.05;
        }

        /* BODY: 3 columns - photo | info | qr */
        .body { position: relative; height: calc(100% - 11mm - 1.5mm); }

        .col-photo {
            position: absolute;
            left: 0;
            top: 0;
            width: 18mm;
            height: 24mm;
            background: rgba(255,255,255,0.12);
            border: 0.4mm solid rgba(255,255,255,0.4);
            border-radius: 1.5mm;
            overflow: hidden;
            text-align: center;
        }
        .col-photo img { width: 100%; height: 100%; object-fit: cover; }
        .col-photo .placeholder {
            color: rgba(255,255,255,0.5);
            font-size: 9px;
            line-height: 24mm;
            font-weight: bold;
        }

        .col-info {
            position: absolute;
            left: 19.5mm;
            top: 0;
            right: 24mm;
        }
        .name {
            font-size: 11.5px;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.1;
            margin-bottom: 1.5mm;
            letter-spacing: -0.05mm;
        }
        .info-row {
            font-size: 7.5px;
            line-height: 1.5;
            color: rgba(255,255,255,0.95);
        }
        .info-row .label {
            display: inline-block;
            width: 9mm;
            color: rgba(255,255,255,0.7);
            font-size: 7px;
        }
        .info-row .val { font-weight: bold; }
        .info-row .val.mono { font-family: DejaVu Sans Mono, monospace; }

        .col-qr {
            position: absolute;
            right: 0;
            top: 0;
            width: 22mm;
            text-align: center;
        }
        .qr-frame {
            background: #ffffff;
            padding: 0.8mm;
            border-radius: 1mm;
            display: inline-block;
        }
        .qr-frame img { width: 19mm; height: 19mm; display: block; }
        .qr-token {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 5.5px;
            color: rgba(255,255,255,0.7);
            margin-top: 0.8mm;
            letter-spacing: 0.2mm;
            word-break: break-all;
        }

        .footer-tag {
            position: absolute;
            bottom: 1mm;
            right: 3mm;
            font-size: 6px;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.3mm;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="grid-wrap">
        @foreach($cards as $c)
            @php $s = $c['student']; @endphp
            <div class="card">
                <div class="bg-layer-1"></div>
                <div class="bg-layer-2"></div>
                <div class="bg-blob"></div>
                <div class="bg-layer-3"></div>

                <div class="inner">
                    {{-- Header --}}
                    <div class="header">
                        <div class="logo">
                            @if($settings->logo && file_exists(public_path('storage/'.$settings->logo)))
                                <img src="{{ public_path('storage/'.$settings->logo) }}" alt="">
                            @endif
                        </div>
                        <div class="head-text">
                            <div class="head-eyebrow">Kartu Pelajar</div>
                            <div class="head-school">{{ \Illuminate\Support\Str::limit($settings->school_name, 38) }}</div>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="body">
                        <div class="col-photo">
                            @if($s->photo && file_exists(public_path('storage/'.$s->photo)))
                                <img src="{{ public_path('storage/'.$s->photo) }}" alt="">
                            @else
                                <div class="placeholder">FOTO</div>
                            @endif
                        </div>

                        <div class="col-info">
                            <div class="name">{{ \Illuminate\Support\Str::limit($s->name, 26) }}</div>
                            <div class="info-row"><span class="label">NIS</span><span class="val mono">{{ $s->nis }}</span></div>
                            @if($s->nisn)
                                <div class="info-row"><span class="label">NISN</span><span class="val mono">{{ $s->nisn }}</span></div>
                            @endif
                            <div class="info-row"><span class="label">Kelas</span><span class="val">{{ $s->schoolClass?->name ?? '—' }}</span></div>
                            @if($s->birth_date)
                                <div class="info-row"><span class="label">Lahir</span><span>{{ $s->birth_place ? \Illuminate\Support\Str::limit($s->birth_place, 14).', ' : '' }}{{ $s->birth_date->format('d-m-Y') }}</span></div>
                            @endif
                        </div>

                        <div class="col-qr">
                            <div class="qr-frame">
                                <img src="{{ $c['qr'] }}" alt="QR">
                            </div>
                            <div class="qr-token">{{ $s->qr_token }}</div>
                        </div>
                    </div>

                    <div class="footer-tag">scan untuk absen</div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
