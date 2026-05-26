<x-filament-panels::page>
    {{-- Info Siswa --}}
    <div style="display:flex;justify-content:flex-end;margin-bottom:8px;">
        <a href="{{ route('print.tahfidz-report', $record) }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:linear-gradient(135deg,#4338ca,#6366f1);color:white;font-size:13px;font-weight:700;text-decoration:none;box-shadow:0 4px 12px rgba(67,56,202,0.3);transition:all 0.2s;"
           onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 16px rgba(67,56,202,0.4)'"
           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 12px rgba(67,56,202,0.3)'">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
            Cetak Report
        </a>
    </div>

    {{-- Info Siswa --}}
    <div style="border-radius:14px;border:1px solid rgba(229,231,235,0.7);background:white;padding:20px 24px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
            <div>
                <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Nama Siswa</div>
                <div style="font-size:16px;font-weight:700;color:#1f2937;margin-top:4px;">{{ $record->student->name }}</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Kelas Tahfidz</div>
                <div style="font-size:14px;font-weight:600;color:#059669;margin-top:4px;">{{ $record->tahfidzClass?->name ?? '—' }}</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Kelas Sekolah</div>
                <div style="font-size:14px;font-weight:600;color:#1f2937;margin-top:4px;">{{ $record->student->schoolClass?->name ?? '—' }}</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6;">
            <div>
                <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">NIS</div>
                <div style="font-size:14px;font-weight:600;color:#1f2937;margin-top:4px;font-family:ui-monospace,monospace;">{{ $record->student->nis ?? '—' }}</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Target Surah</div>
                <div style="font-size:14px;font-weight:600;color:#1f2937;margin-top:4px;">{{ $record->surah_target }}</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Progres</div>
                <div style="font-size:14px;font-weight:700;margin-top:4px;color:{{ $record->progres_present >= 80 ? '#059669' : ($record->progres_present >= 50 ? '#d97706' : '#dc2626') }};">
                    {{ $record->grades->count() }} / {{ $record->surah_target }} ({{ $record->progres_present }}%)
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Surat yang Sudah Diselesaikan --}}
    <div style="border-radius:14px;border:1px solid rgba(229,231,235,0.7);background:white;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding:16px 24px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:14px;font-weight:800;color:#1f2937;">Daftar Surat yang Diselesaikan</div>
            <div style="font-size:12px;color:#6b7280;">
                Rata-rata: <span style="font-weight:700;color:#4f46e5;">{{ $record->nilai_rata_rata }}</span>
            </div>
        </div>

        @php $grades = $this->getGrades(); @endphp

        @if($grades->isEmpty())
            <div style="padding:48px 24px;text-align:center;color:#9ca3af;">
                <div style="font-size:14px;font-weight:600;">Belum ada surat yang diselesaikan.</div>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:11px 16px;text-align:center;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;width:50px;">#</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">Surat</th>
                            <th style="padding:11px 16px;text-align:center;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;width:100px;">Nilai</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">Guru Penguji</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">Deskripsi</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;width:110px;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $i => $grade)
                            <tr style="background:{{ $i % 2 === 0 ? 'white' : '#f8fafc' }};border-bottom:1px solid #f1f5f9;">
                                <td style="padding:10px 16px;text-align:center;color:#9ca3af;font-size:12px;">{{ $i + 1 }}</td>
                                <td style="padding:10px 16px;font-weight:600;color:#1f2937;">{{ $grade->surah }}</td>
                                <td style="padding:10px 16px;text-align:center;">
                                    @php
                                        $color = $grade->score >= 80 ? '#059669' : ($grade->score >= 60 ? '#d97706' : '#dc2626');
                                        $bg = $grade->score >= 80 ? '#f0fdf4' : ($grade->score >= 60 ? '#fffbeb' : '#fef2f2');
                                    @endphp
                                    <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;color:{{ $color }};background:{{ $bg }};">
                                        {{ $grade->score }}
                                    </span>
                                </td>
                                <td style="padding:10px 16px;color:#4b5563;font-size:12px;">{{ $grade->teacher?->name ?? '—' }}</td>
                                <td style="padding:10px 16px;color:#6b7280;font-size:12px;">{{ $grade->description ?? '—' }}</td>
                                <td style="padding:10px 16px;color:#6b7280;font-size:12px;">{{ $grade->created_at?->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament-panels::page>
