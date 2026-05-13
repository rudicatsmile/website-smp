<div class="space-y-6">
    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#4338ca 0%,#6366f1 50%,#818cf8 100%);border-radius:16px;padding:24px;color:white;box-shadow:0 10px 25px -5px rgba(67,56,202,0.35);">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;opacity:0.75;font-weight:700;">Sahabat Qur'an</div>
                <h1 style="font-size:24px;font-weight:800;margin-top:4px;line-height:1.2;">Raport Tahfidz</h1>
                <p style="font-size:14px;opacity:0.9;margin-top:6px;">
                    {{ $student->name }}
                    &middot; Kelas <strong>{{ $student->schoolClass?->name ?? '—' }}</strong>
                    &middot; NIS: {{ $student->nis ?? '—' }}
                </p>
            </div>
            <div style="text-align:right;opacity:0.85;">
                <div style="font-size:11px;">{{ now()->translatedFormat('l') }}</div>
                <div style="font-size:15px;font-weight:700;">{{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>

    @php
        $participant = $student->tahfidzParticipant;
        $grades      = $student->tahfidzGrades;
        $selesai     = $grades->count();
        $target      = $participant?->surah_target ?? 0;
        $progres     = $target > 0 ? round(($selesai / $target) * 100, 1) : 0;
        $rata        = $grades->avg('score') ? round($grades->avg('score'), 1) : 0;
    @endphp

    {{-- Stats pills --}}
    @if($participant)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">
            <div style="border-radius:12px;background:white;border:1px solid rgba(229,231,235,0.7);padding:16px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#6b7280;">Surah Selesai</div>
                <div style="font-size:28px;font-weight:800;color:#4338ca;margin-top:4px;">{{ $selesai }}</div>
                <div style="font-size:11px;color:#9ca3af;">dari {{ $target }} target</div>
            </div>
            <div style="border-radius:12px;background:white;border:1px solid rgba(229,231,235,0.7);padding:16px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#6b7280;">Progres</div>
                <div style="font-size:28px;font-weight:800;color:{{ $progres >= 80 ? '#10b981' : ($progres >= 50 ? '#f59e0b' : '#ef4444') }};margin-top:4px;">{{ $progres }}%</div>
                <div style="font-size:11px;color:#9ca3af;">pencapaian</div>
            </div>
            <div style="border-radius:12px;background:white;border:1px solid rgba(229,231,235,0.7);padding:16px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#6b7280;">Nilai Rata-Rata</div>
                <div style="font-size:28px;font-weight:800;color:#0ea5e9;margin-top:4px;">{{ $rata }}</div>
                <div style="font-size:11px;color:#9ca3af;">dari 100</div>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div style="border-radius:14px;border:1px solid rgba(229,231,235,0.7);background:white;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="background:white;padding:14px 20px;border-bottom:1px solid #f1f5f9;">
            <div style="font-size:14px;font-weight:800;color:#1f2937;">Daftar Hafalan</div>
        </div>

        @if($grades->count() > 0)
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:white;border-bottom:2px solid #e5e7eb;">
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#374151;font-size:12px;">#</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#374151;font-size:12px;">Surah</th>
                            <th style="padding:11px 16px;text-align:center;font-weight:700;color:#374151;font-size:12px;">Nilai</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#374151;font-size:12px;">Deskripsi</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#374151;font-size:12px;">Guru</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#374151;font-size:12px;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $i => $grade)
                            <tr style="background:{{ $i % 2 === 0 ? 'white' : '#f8fafc' }};border-bottom:1px solid #f1f5f9;">
                                <td style="padding:10px 16px;color:#9ca3af;font-size:12px;">{{ $i + 1 }}</td>
                                <td style="padding:10px 16px;font-weight:600;color:#1f2937;">{{ $grade->surah }}</td>
                                <td style="padding:10px 16px;text-align:center;">
                                    <span style="display:inline-block;padding:3px 10px;border-radius:9999px;font-weight:700;font-size:12px;
                                        background:{{ $grade->score >= 80 ? '#d1fae5' : ($grade->score >= 60 ? '#fef3c7' : '#fee2e2') }};
                                        color:{{ $grade->score >= 80 ? '#065f46' : ($grade->score >= 60 ? '#92400e' : '#991b1b') }};">
                                        {{ $grade->score }}
                                    </span>
                                </td>
                                <td style="padding:10px 16px;color:#6b7280;font-size:12px;">{{ $grade->description ?? '—' }}</td>
                                <td style="padding:10px 16px;color:#6b7280;font-size:12px;">{{ $grade->teacher?->name ?? '—' }}</td>
                                <td style="padding:10px 16px;color:#9ca3af;font-size:12px;">{{ $grade->created_at->translatedFormat('d F Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding:48px 24px;text-align:center;color:#9ca3af;">
                <div style="font-size:13px;font-weight:600;">Belum ada data hafalan.</div>
            </div>
        @endif
    </div>

    {{-- Back button --}}
    <div>
        <a href="{{ route('portal.dashboard') }}" wire:navigate
           style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:10px;background:#4338ca;color:white;font-size:13px;font-weight:700;text-decoration:none;">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
