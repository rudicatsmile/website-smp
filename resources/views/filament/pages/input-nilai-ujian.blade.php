<x-filament-panels::page>

    @php
        $exam     = $this->exam;
        $students = $this->students;
    @endphp

    {{-- ═══ HERO ═══ --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:22px 28px; color:white; background:linear-gradient(135deg,#b91c1c 0%,#dc2626 50%,#ef4444 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15);">
        <div style="position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:9999px; background:rgba(255,255,255,0.07); filter:blur(40px);"></div>
        <div style="position:relative; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:52px; height:52px; border-radius:14px; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; border:1.5px solid rgba(255,255,255,0.3); flex-shrink:0;">
                    <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <div style="font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; opacity:0.8; margin-bottom:4px;">
                        Input Nilai Ujian
                        @if($exam)
                            &middot; {{ \App\Models\ExamSession::TYPES[$exam->exam_type] ?? strtoupper($exam->exam_type) }}
                        @endif
                    </div>
                    <div style="font-size:19px; font-weight:800; letter-spacing:-0.02em;">{{ $exam?->title ?? '—' }}</div>
                    <div style="font-size:12px; opacity:0.85; margin-top:4px; display:flex; gap:12px; flex-wrap:wrap;">
                        <span>{{ $exam?->schoolClass?->name ?? '—' }}</span>
                        <span>&middot; {{ $exam?->subject?->name ?? '—' }}</span>
                        <span>&middot; {{ $exam?->exam_date?->isoFormat('dddd, D MMMM Y') ?? '—' }}</span>
                        <span>&middot; TA {{ $exam?->academic_year }} {{ $exam?->semester }}</span>
                        <span>&middot; Maks: <strong>{{ $exam?->max_score }}</strong></span>
                    </div>
                </div>
            </div>
            <a href="{{ $this->backUrl() }}"
                style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:10px; font-size:12px; font-weight:700; background:rgba(255,255,255,0.18); color:white; text-decoration:none; border:1px solid rgba(255,255,255,0.3); flex-shrink:0; transition:all 0.2s;"
                onmouseover="this.style.background='rgba(255,255,255,0.28)'" onmouseout="this.style.background='rgba(255,255,255,0.18)'">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- ═══ INFO ABSENSI ═══ --}}
    @php
        $hasAbsensiData = $students->isNotEmpty() && $students->first()->attendance_status !== null;
    @endphp
    @if(! $hasAbsensiData && $students->isNotEmpty())
        <div style="display:flex; align-items:flex-start; gap:12px; padding:14px 18px; border-radius:12px; background:#dbeafe; border:1.5px solid #3b82f6; color:#1e40af;">
            <svg style="width:20px;height:20px; flex-shrink:0; margin-top:1px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div style="font-size:12px; line-height:1.5;">
                Tidak ada data absensi di tanggal ujian ini. Semua siswa aktif ditampilkan. Anda tetap bisa input nilai untuk semua siswa.
            </div>
        </div>
    @endif

    {{-- ═══ TABEL INPUT NILAI ═══ --}}
    @if(! $exam)
        <div style="text-align:center; padding:48px; color:#94a3b8; font-size:13px;">Data ujian tidak ditemukan.</div>
    @elseif($students->isEmpty())
        <div style="text-align:center; padding:48px; color:#94a3b8; font-size:13px;">Tidak ada siswa aktif di kelas ini.</div>
    @else
        <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:white; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">

            {{-- Legend --}}
            @if($hasAbsensiData)
                <div style="padding:12px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                    <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:#475569;">Status absensi:</div>
                    <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; color:#065f46;">
                        <span style="width:10px; height:10px; border-radius:50%; background:#d1fae5; border:1.5px solid #059669;"></span> Hadir
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; color:#991b1b;">
                        <span style="width:10px; height:10px; border-radius:50%; background:#fee2e2; border:1.5px solid #dc2626;"></span> Alpa
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; color:#475569;">
                        <span style="width:10px; height:10px; border-radius:50%; background:#f1f5f9; border:1.5px solid #94a3b8;"></span> Lainnya (Sakit/Izin)
                    </span>
                </div>
            @endif

            <table style="width:100%; border-collapse:collapse; font-size:12px;">
                <thead>
                    <tr style="background:#fef2f2; border-bottom:2px solid #fca5a5;">
                        <th style="padding:10px 10px; text-align:center; font-weight:700; color:#991b1b; width:36px;">No</th>
                        <th style="padding:10px 14px; text-align:left; font-weight:700; color:#991b1b;">Nama Siswa</th>
                        <th style="padding:10px 10px; text-align:center; font-weight:700; color:#991b1b; width:90px;">NIS</th>
                        @if($hasAbsensiData)
                            <th style="padding:10px 10px; text-align:center; font-weight:700; color:#991b1b; width:80px;">Status</th>
                        @endif
                        <th style="padding:10px 14px; text-align:center; font-weight:700; color:#991b1b; width:120px;">
                            Nilai <span style="font-weight:400; font-size:10px;">(maks {{ $exam->max_score }})</span>
                        </th>
                        <th style="padding:10px 10px; text-align:center; font-weight:700; color:#991b1b; width:80px;">Remedial</th>
                        <th style="padding:10px 14px; text-align:left; font-weight:700; color:#991b1b; min-width:160px;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                        @php
                            $status = $student->attendance_status;
                            $rowBg  = $i % 2 === 0 ? 'white' : '#fafafa';
                            if ($status === 'alpa') $rowBg = '#fff5f5';
                        @endphp
                        <tr style="background:{{ $rowBg }}; border-bottom:1px solid #e8eef4;">
                            <td style="padding:9px 10px; text-align:center; color:#94a3b8; font-size:10px;">{{ $i + 1 }}</td>
                            <td style="padding:9px 14px; font-weight:600; color:#1e293b;" class="dark:text-white">
                                {{ $student->name }}
                            </td>
                            <td style="padding:9px 10px; text-align:center; color:#64748b; font-family:ui-monospace,monospace; font-size:10px;">
                                {{ $student->nis ?? '—' }}
                            </td>
                            @if($hasAbsensiData)
                                <td style="padding:9px 10px; text-align:center;">
                                    @if($status === 'hadir')
                                        <span style="display:inline-block; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:#d1fae5; color:#065f46;">Hadir</span>
                                    @elseif($status === 'alpa')
                                        <span style="display:inline-block; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:#fee2e2; color:#991b1b;">Alpa</span>
                                    @else
                                        <span style="display:inline-block; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:#f1f5f9; color:#64748b;">Lain</span>
                                    @endif
                                </td>
                            @endif
                            <td style="padding:7px 14px; text-align:center;">
                                <input
                                    type="number"
                                    wire:model="scores.{{ $student->id }}.score"
                                    min="0"
                                    max="{{ $exam->max_score }}"
                                    step="0.5"
                                    placeholder="—"
                                    style="width:80px; border-radius:8px; padding:6px 10px; font-size:13px; font-weight:600; text-align:center; border:1.5px solid #e2e8f0; background:white; color:#1e293b; outline:none;"
                                >
                            </td>
                            <td style="padding:7px 14px; text-align:center;">
                                <input
                                    type="checkbox"
                                    wire:model="scores.{{ $student->id }}.is_remedial"
                                    style="width:16px; height:16px; accent-color:#dc2626; cursor:pointer;"
                                >
                            </td>
                            <td style="padding:7px 14px;">
                                <input
                                    type="text"
                                    wire:model="scores.{{ $student->id }}.notes"
                                    placeholder="Catatan opsional..."
                                    style="width:100%; border-radius:8px; padding:6px 10px; font-size:12px; border:1.5px solid #e2e8f0; background:white; color:#475569; outline:none;"
                                >
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Footer --}}
            <div style="padding:16px 20px; background:#fef2f2; border-top:1px solid #fca5a5; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                <div style="font-size:11px; color:#64748b;">
                    {{ $students->count() }} siswa &middot; Centang kolom <strong>Remedial</strong> jika siswa mengikuti ujian remedial
                </div>
                <button wire:click="save"
                    style="display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border-radius:10px; font-size:13px; font-weight:800; color:white; background:linear-gradient(135deg,#b91c1c,#dc2626); border:none; cursor:pointer; box-shadow:0 4px 12px -2px rgba(220,38,38,0.45); transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan Nilai Ujian
                </button>
            </div>
        </div>
    @endif

</x-filament-panels::page>
