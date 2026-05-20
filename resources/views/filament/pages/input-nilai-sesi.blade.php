<x-filament-panels::page>

    @php
        $assessment = $this->assessment;
        $session    = $assessment?->lessonSession;
        $students   = $this->students;
    @endphp

    {{-- ═══ HERO ═══ --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:22px 28px; color:white; background:linear-gradient(135deg,#7c3aed 0%,#4f46e5 60%,#1d4ed8 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15);">
        <div style="position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:9999px; background:rgba(255,255,255,0.07); filter:blur(40px);"></div>
        <div style="position:relative; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:52px; height:52px; border-radius:14px; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; border:1.5px solid rgba(255,255,255,0.3); flex-shrink:0;">
                    <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <div style="font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; opacity:0.8; margin-bottom:4px;">Input Nilai Penilaian</div>
                    <div style="font-size:19px; font-weight:800; letter-spacing:-0.02em;">{{ $assessment?->title ?? '—' }}</div>
                    <div style="font-size:12px; opacity:0.85; margin-top:4px; display:flex; gap:12px; flex-wrap:wrap;">
                        <span>{{ $session?->schoolClass?->name ?? '—' }}</span>
                        <span>&middot; {{ $session?->subject?->name ?? '—' }}</span>
                        <span>&middot; {{ $session?->session_date?->isoFormat('dddd, D MMMM Y') ?? '—' }}</span>
                        <span>&middot; Maks: <strong>{{ $assessment?->max_score }}</strong></span>
                    </div>
                </div>
            </div>
            <a href="{{ $this->backUrl() }}"
                style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:10px; font-size:12px; font-weight:700; background:rgba(255,255,255,0.18); color:white; text-decoration:none; border:1px solid rgba(255,255,255,0.3); flex-shrink:0; transition:all 0.2s;"
                onmouseover="this.style.background='rgba(255,255,255,0.28)'" onmouseout="this.style.background='rgba(255,255,255,0.18)'">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Sesi
            </a>
        </div>
    </div>

    {{-- ═══ ALERT: belum absensi ═══ --}}
    @if(! $this->hasAbsensi)
        <div style="display:flex; align-items:flex-start; gap:12px; padding:14px 18px; border-radius:12px; background:#fef3c7; border:1.5px solid #f59e0b; color:#92400e;">
            <svg style="width:20px;height:20px; flex-shrink:0; margin-top:1px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <div style="font-weight:800; font-size:13px; margin-bottom:2px;">Belum Ada Absensi</div>
                <div style="font-size:12px; line-height:1.5;">
                    Absensi pada tanggal sesi ini (<strong>{{ $session?->session_date?->isoFormat('D MMMM Y') }}</strong>) belum diisi.
                    Siswa yang tidak hadir tetap ditampilkan. Disarankan mengisi absensi terlebih dahulu.
                </div>
            </div>
        </div>
    @endif

    {{-- ═══ TABEL INPUT NILAI ═══ --}}
    @if(! $assessment)
        <div style="text-align:center; padding:48px; color:#94a3b8; font-size:13px;">Data penilaian tidak ditemukan.</div>
    @elseif($students->isEmpty())
        <div style="text-align:center; padding:48px; color:#94a3b8; font-size:13px;">Tidak ada siswa aktif di kelas ini.</div>
    @else
        <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:white; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">

            {{-- Legend --}}
            <div style="padding:14px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:#475569;">Keterangan:</div>
                <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; color:#065f46;">
                    <span style="width:10px; height:10px; border-radius:50%; background:#d1fae5; border:1.5px solid #059669;"></span> Hadir
                </span>
                <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; color:#991b1b;">
                    <span style="width:10px; height:10px; border-radius:50%; background:#fee2e2; border:1.5px solid #dc2626;"></span> Tidak Hadir (input dinonaktifkan)
                </span>
                <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; color:#92400e;">
                    <span style="width:10px; height:10px; border-radius:50%; background:#fef3c7; border:1.5px solid #f59e0b;"></span> Data absensi belum ada
                </span>
            </div>

            <table style="width:100%; border-collapse:collapse; font-size:12px;">
                <thead>
                    <tr style="background:#f0f4f8; border-bottom:2px solid #d0dde8;">
                        <th style="padding:10px 10px; text-align:center; font-weight:700; color:#1e40af; width:36px;">No</th>
                        <th style="padding:10px 14px; text-align:left; font-weight:700; color:#1e40af;">Nama Siswa</th>
                        <th style="padding:10px 10px; text-align:center; font-weight:700; color:#1e40af; width:90px;">NIS</th>
                        <th style="padding:10px 10px; text-align:center; font-weight:700; color:#1e40af; width:80px;">Status</th>
                        <th style="padding:10px 14px; text-align:center; font-weight:700; color:#1e40af; width:120px;">
                            Nilai <span style="font-weight:400; font-size:10px;">(maks {{ $assessment->max_score }})</span>
                        </th>
                        <th style="padding:10px 14px; text-align:left; font-weight:700; color:#1e40af; min-width:180px;">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                        @php
                            $isPresent = $student->is_present;
                            $noAbsensi = $student->no_absensi;
                            if ($noAbsensi) {
                                $rowBg = '#fffbeb';
                            } elseif ($isPresent) {
                                $rowBg = $i % 2 === 0 ? 'white' : '#f8fafc';
                            } else {
                                $rowBg = '#fff5f5';
                            }
                        @endphp
                        <tr style="background:{{ $rowBg }}; border-bottom:1px solid #e8eef4;">
                            <td style="padding:9px 10px; text-align:center; color:#94a3b8; font-size:10px;">{{ $i + 1 }}</td>
                            <td style="padding:9px 14px; font-weight:600; color:#1e293b;" class="dark:text-white">
                                {{ $student->name }}
                            </td>
                            <td style="padding:9px 10px; text-align:center; color:#64748b; font-family:ui-monospace,monospace; font-size:10px;">
                                {{ $student->nis ?? '—' }}
                            </td>
                            <td style="padding:9px 10px; text-align:center;">
                                @if($noAbsensi)
                                    <span style="display:inline-block; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:#fef3c7; color:#92400e;">Blm Absen</span>
                                @elseif($isPresent)
                                    <span style="display:inline-block; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:#d1fae5; color:#065f46;">Hadir</span>
                                @else
                                    <span style="display:inline-block; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background:#fee2e2; color:#991b1b;">Tdk Hadir</span>
                                @endif
                            </td>
                            <td style="padding:7px 14px; text-align:center;">
                                <input
                                    type="number"
                                    wire:model="scores.{{ $student->id }}.score"
                                    min="0"
                                    max="{{ $assessment->max_score }}"
                                    step="0.5"
                                    placeholder="—"
                                    {{ ! $isPresent && ! $noAbsensi ? 'disabled' : '' }}
                                    style="width:80px; border-radius:8px; padding:6px 10px; font-size:13px; font-weight:600; text-align:center;
                                           border:1.5px solid {{ ! $isPresent && ! $noAbsensi ? '#fca5a5' : '#e2e8f0' }};
                                           background:{{ ! $isPresent && ! $noAbsensi ? '#fff5f5' : 'white' }};
                                           color:{{ ! $isPresent && ! $noAbsensi ? '#fca5a5' : '#1e293b' }};
                                           outline:none;"
                                >
                            </td>
                            <td style="padding:7px 14px;">
                                <input
                                    type="text"
                                    wire:model="scores.{{ $student->id }}.notes"
                                    placeholder="Catatan opsional..."
                                    {{ ! $isPresent && ! $noAbsensi ? 'disabled' : '' }}
                                    style="width:100%; border-radius:8px; padding:6px 10px; font-size:12px;
                                           border:1.5px solid #e2e8f0; background:white; color:#475569; outline:none;"
                                >
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Footer action --}}
            <div style="padding:16px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                <div style="font-size:11px; color:#64748b;">
                    {{ $students->where('is_present', true)->count() }} siswa hadir
                    &middot; {{ $students->count() }} total
                </div>
                <button wire:click="save"
                    style="display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border-radius:10px; font-size:13px; font-weight:800; color:white; background:linear-gradient(135deg,#7c3aed,#4f46e5); border:none; cursor:pointer; box-shadow:0 4px 12px -2px rgba(79,70,229,0.45); transition:all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan Nilai
                </button>
            </div>
        </div>
    @endif

</x-filament-panels::page>
