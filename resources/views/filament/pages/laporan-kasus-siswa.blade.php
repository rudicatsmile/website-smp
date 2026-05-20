<x-filament-panels::page>

    @php
        $data    = $this->reportData;
        $rows    = $data['rows']    ?? [];
        $class   = $data['class']   ?? null;
        $subject = $data['subject'] ?? null;

        $totalSelesai    = collect($rows)->where('selesai', true)->count();
        $totalTidak      = collect($rows)->where('selesai', false)->count();
    @endphp

    {{-- ═══ HERO ═══ --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:22px 28px; color:white; background:linear-gradient(135deg,#7c2d12 0%,#c2410c 55%,#ea580c 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15); margin-bottom:0;">
        <div style="position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:9999px; background:rgba(255,255,255,0.06); filter:blur(50px);"></div>
        <div style="position:relative;">
            <div style="font-size:10px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; opacity:0.75; margin-bottom:6px;">Laporan Akademik</div>
            <div style="font-size:22px; font-weight:800; letter-spacing:-0.02em; margin-bottom:4px;">Catatan Kasus Peserta Didik</div>
            @if($this->show_report && $class && $subject)
                <div style="font-size:12px; opacity:0.85; display:flex; gap:12px; flex-wrap:wrap;">
                    <span>{{ $class->name }}</span>
                    <span>&middot;</span>
                    <span>{{ $subject->name }}</span>
                    <span>&middot;</span>
                    <span>{{ \Carbon\Carbon::parse($this->date_from)->format('d M Y') }} – {{ \Carbon\Carbon::parse($this->date_to)->format('d M Y') }}</span>
                    <span>&middot;</span>
                    <span>{{ count($rows) }} kasus</span>
                    @if(count($rows) > 0)
                        <span>&middot;</span>
                        <span style="background:rgba(255,255,255,0.2); padding:1px 8px; border-radius:999px;">S: {{ $totalSelesai }}</span>
                        <span style="background:rgba(255,255,255,0.2); padding:1px 8px; border-radius:999px;">TS: {{ $totalTidak }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ═══ FILTER CARD ═══ --}}
    <div style="border-radius:16px; border:1px solid #e2e8f0; background:white; padding:20px 24px; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
            <div style="width:8px; height:8px; border-radius:50%; background:#ea580c;"></div>
            <span style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:#64748b;">Filter Laporan</span>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; align-items:end;">

            {{-- Kelas --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#374151; margin-bottom:5px;">Kelas <span style="color:#ef4444;">*</span></label>
                <select wire:model="school_class_id" style="width:100%; border-radius:8px; padding:8px 10px; font-size:13px; border:1.5px solid #e2e8f0; background:white; color:#374151; outline:none;">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($this->classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
                @error('school_class_id')<div style="color:#ef4444;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            {{-- Mata Pelajaran --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#374151; margin-bottom:5px;">Mata Pelajaran <span style="color:#ef4444;">*</span></label>
                <select wire:model="material_category_id" style="width:100%; border-radius:8px; padding:8px 10px; font-size:13px; border:1.5px solid #e2e8f0; background:white; color:#374151; outline:none;">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($this->subjects as $subj)
                        <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                    @endforeach
                </select>
                @error('material_category_id')<div style="color:#ef4444;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            {{-- Tanggal Dari --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#374151; margin-bottom:5px;">Dari Tanggal <span style="color:#ef4444;">*</span></label>
                <input type="date" wire:model="date_from"
                    style="width:100%; border-radius:8px; padding:8px 10px; font-size:13px; border:1.5px solid #e2e8f0; background:white; color:#374151; outline:none; box-sizing:border-box;">
                @error('date_from')<div style="color:#ef4444;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            {{-- Tanggal Sampai --}}
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#374151; margin-bottom:5px;">Sampai Tanggal <span style="color:#ef4444;">*</span></label>
                <input type="date" wire:model="date_to"
                    style="width:100%; border-radius:8px; padding:8px 10px; font-size:13px; border:1.5px solid #e2e8f0; background:white; color:#374151; outline:none; box-sizing:border-box;">
                @error('date_to')<div style="color:#ef4444;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            {{-- Tombol --}}
            <div style="display:flex; gap:8px; align-items:center;">
                <button wire:click="generate"
                    style="flex:1; padding:9px 18px; border-radius:8px; font-size:13px; font-weight:700; color:white; background:#c2410c; border:none; cursor:pointer;">
                    Tampilkan
                </button>
                @if($this->show_report)
                    <button wire:click="reset_filter"
                        style="padding:9px 12px; border-radius:8px; font-size:12px; font-weight:600; color:#475569; background:#f1f5f9; border:1px solid #e2e8f0; cursor:pointer;">
                        Reset
                    </button>
                @endif
            </div>
        </div>

        {{-- Export --}}
        @if($this->show_report)
            <div style="margin-top:16px; padding-top:14px; border-top:1px solid #f1f5f9; display:flex; gap:8px; flex-wrap:wrap;">
                <span style="font-size:11px; font-weight:700; color:#94a3b8; align-self:center; margin-right:4px;">Export:</span>
                <button wire:click="exportExcel"
                    style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; border-radius:8px; font-size:12px; font-weight:700; color:#065f46; background:#d1fae5; border:1px solid #6ee7b7; cursor:pointer;">
                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                </button>
                <button wire:click="exportPdf"
                    style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; border-radius:8px; font-size:12px; font-weight:700; color:#991b1b; background:#fee2e2; border:1px solid #fca5a5; cursor:pointer;">
                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </button>
            </div>
        @endif
    </div>

    {{-- ═══ TABEL ═══ --}}
    @if($this->show_report)
        @if(empty($rows))
            <div style="text-align:center; padding:60px 20px; color:#94a3b8; font-size:13px; background:white; border-radius:16px; border:1px solid #e2e8f0;">
                <div style="font-size:32px; margin-bottom:10px;">📋</div>
                Tidak ada kasus peserta didik pada periode dan filter yang dipilih.
            </div>
        @else
            {{-- Summary chips --}}
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="border-radius:12px; padding:10px 18px; background:white; border:1px solid #e2e8f0; display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:8px; background:#fee2e2; display:flex; align-items:center; justify-content:center; font-size:16px;">📋</div>
                    <div>
                        <div style="font-size:18px; font-weight:800; color:#1e293b;">{{ count($rows) }}</div>
                        <div style="font-size:10px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.06em;">Total Kasus</div>
                    </div>
                </div>
                <div style="border-radius:12px; padding:10px 18px; background:white; border:1px solid #e2e8f0; display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:8px; background:#dcfce7; display:flex; align-items:center; justify-content:center; font-size:16px;">✅</div>
                    <div>
                        <div style="font-size:18px; font-weight:800; color:#166534;">{{ $totalSelesai }}</div>
                        <div style="font-size:10px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.06em;">Selesai</div>
                    </div>
                </div>
                <div style="border-radius:12px; padding:10px 18px; background:white; border:1px solid #e2e8f0; display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:8px; background:#fee2e2; display:flex; align-items:center; justify-content:center; font-size:16px;">⏳</div>
                    <div>
                        <div style="font-size:18px; font-weight:800; color:#991b1b;">{{ $totalTidak }}</div>
                        <div style="font-size:10px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.06em;">Tidak Selesai</div>
                    </div>
                </div>
            </div>

            <div style="border-radius:16px; border:1px solid #e2e8f0; background:white; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.06);" class="dark:bg-white/5 dark:border-white/10">
                {{-- Legend --}}
                <div style="padding:8px 18px; background:#fff7ed; border-bottom:1px solid #fed7aa; font-size:11px; color:#9a3412; display:flex; gap:16px; flex-wrap:wrap; align-items:center;">
                    <span style="font-weight:800;">Keterangan:</span>
                    <span><strong>S</strong> = Selesai</span>
                    <span><strong>TS</strong> = Tidak Selesai</span>
                </div>

                <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                    <table style="width:100%; border-collapse:collapse; font-size:12px;">
                        <thead>
                            <tr style="background:#7c2d12; color:white;">
                                <th style="padding:10px 10px; border:1px solid #9a3412; text-align:center; width:40px;">No</th>
                                <th style="padding:10px 14px; border:1px solid #9a3412; text-align:left; min-width:160px;">Nama Peserta Didik</th>
                                <th style="padding:10px 12px; border:1px solid #9a3412; text-align:center; min-width:90px;">Tanggal</th>
                                <th style="padding:10px 12px; border:1px solid #9a3412; text-align:center; min-width:80px;">Kelas</th>
                                <th style="padding:10px 14px; border:1px solid #9a3412; text-align:left; min-width:220px;">Masalah / Kasus</th>
                                <th style="padding:10px 8px; border:1px solid #9a3412; text-align:center; width:36px; font-weight:800;">S</th>
                                <th style="padding:10px 8px; border:1px solid #9a3412; text-align:center; width:36px; font-weight:800;">TS</th>
                                <th style="padding:10px 14px; border:1px solid #9a3412; text-align:left; min-width:180px;">Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                                @php $bgBase = $loop->odd ? 'white' : '#fafafa'; @endphp
                                <tr style="background:{{ $bgBase }};" onmouseover="this.style.background='#fff7ed'" onmouseout="this.style.background='{{ $bgBase }}'">
                                    <td style="padding:9px 10px; border:1px solid #e2e8f0; text-align:center; color:#94a3b8; font-size:11px;">{{ $row['no'] }}</td>
                                    <td style="padding:9px 14px; border:1px solid #e2e8f0; font-weight:600; color:#1e293b;">
                                        {{ $row['student']?->name ?? '—' }}
                                    </td>
                                    <td style="padding:9px 12px; border:1px solid #e2e8f0; text-align:center; color:#475569; font-size:11px; white-space:nowrap;">
                                        {{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->format('d/m/Y') : '—' }}
                                    </td>
                                    <td style="padding:9px 12px; border:1px solid #e2e8f0; text-align:center;">
                                        <span style="background:#fed7aa; color:#9a3412; font-size:10px; font-weight:700; padding:2px 8px; border-radius:999px;">{{ $row['class'] }}</span>
                                    </td>
                                    <td style="padding:9px 14px; border:1px solid #e2e8f0; color:#374151; font-size:12px; white-space:pre-wrap; max-width:280px;">{{ $row['problem'] }}</td>
                                    <td style="padding:9px 8px; border:1px solid #e2e8f0; text-align:center; font-size:15px; color:#16a34a;">
                                        {{ $row['selesai'] ? '✓' : '' }}
                                    </td>
                                    <td style="padding:9px 8px; border:1px solid #e2e8f0; text-align:center; font-size:15px; color:#dc2626;">
                                        {{ ! $row['selesai'] ? '✓' : '' }}
                                    </td>
                                    <td style="padding:9px 14px; border:1px solid #e2e8f0; color:#475569; font-size:12px; white-space:pre-wrap; max-width:250px;">
                                        {{ $row['follow_up'] ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding:10px 18px; background:#fafafa; border-top:1px solid #e2e8f0; font-size:11px; color:#94a3b8; display:flex; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                    <span>{{ count($rows) }} kasus ditemukan</span>
                    <span>S = Selesai &middot; TS = Tidak Selesai</span>
                </div>
            </div>
        @endif
    @endif

</x-filament-panels::page>
