<x-filament-panels::page>
    @php
        $classes      = \App\Models\SchoolClass::where('is_active', true)->orderBy('grade')->orderBy('section')->get();
        $classStudents = $this->classStudents;
        $selected      = $this->selectedStudents;
        $settings      = app(\App\Settings\GeneralSettings::class);
        $selectedCount = count($student_ids);
        $totalInClass  = $classStudents->count();
        $allSelected   = $totalInClass > 0 && $selectedCount === $totalInClass;
    @endphp

    {{-- HERO HEADER --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:24px 28px; color:white; background:linear-gradient(135deg, #4f46e5 0%, #2563eb 50%, #0891b2 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1);">
        <div style="position:absolute; right:-48px; top:-48px; width:224px; height:224px; border-radius:9999px; background:rgba(255,255,255,0.1); filter:blur(48px);"></div>
        <div style="position:absolute; left:-32px; bottom:-32px; width:192px; height:192px; border-radius:9999px; background:rgba(96,165,250,0.2); filter:blur(48px);"></div>
        <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(255,255,255,0.05), transparent);"></div>

        <div style="position:relative; display:flex; align-items:center; justify-content:space-between; gap:1.5rem; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:1rem; flex:1; min-width:280px;">
                <div style="width:64px; height:64px; border-radius:16px; background:rgba(255,255,255,0.2); backdrop-filter:blur(12px); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); flex-shrink:0; border:2px solid rgba(255,255,255,0.3);">
                    <svg style="width:30px;height:30px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                </div>
                <div>
                    <div style="display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:9999px; background:rgba(255,255,255,0.15); font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;">
                        <span style="width:6px;height:6px;border-radius:9999px;background:#a5b4fc;display:inline-block;"></span>
                        Akademik · Kartu Pelajar
                    </div>
                    <h2 style="font-size:26px; font-weight:800; letter-spacing:-0.025em; line-height:1.15; margin-top:6px;">Cetak Kartu Pelajar</h2>
                    <p style="font-size:13px; opacity:0.85; margin-top:4px;">Generate kartu siswa dengan QR code. Cetak A4, potong & laminating.</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:0.75rem; flex-shrink:0;">
                <div style="text-align:center; background:rgba(255,255,255,0.15); backdrop-filter:blur(8px); border-radius:12px; padding:10px 18px; border:1px solid rgba(255,255,255,0.2);">
                    <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.1em; opacity:0.8; font-weight:700;">Dipilih</div>
                    <div style="font-size:28px; font-weight:800; line-height:1.1; margin-top:2px;">{{ $selectedCount }}</div>
                </div>
                <div style="text-align:center; background:rgba(255,255,255,0.15); backdrop-filter:blur(8px); border-radius:12px; padding:10px 18px; border:1px solid rgba(255,255,255,0.2);">
                    <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.1em; opacity:0.8; font-weight:700;">Total Kelas</div>
                    <div style="font-size:28px; font-weight:800; line-height:1.1; margin-top:2px;">{{ $totalInClass }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- STEP 1: PILIH KELAS --}}
    <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:rgba(255,255,255,0.9); padding:20px 22px; box-shadow:0 1px 3px 0 rgba(0,0,0,0.05);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid rgba(229,231,235,0.6);">
            <div style="width:32px; height:32px; border-radius:9999px; background:linear-gradient(135deg,#4f46e5,#2563eb); color:white; font-weight:800; font-size:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 2px 6px rgba(79,70,229,0.4);">1</div>
            <div>
                <div style="font-size:13px; font-weight:800; color:#1f2937;">Pilih Kelas</div>
                <div style="font-size:11px; color:#6b7280; margin-top:1px;">Tentukan kelas yang akan dicetak kartunya.</div>
            </div>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(100px, 1fr)); gap:10px;">
            @foreach($classes as $c)
                @php $active = $school_class_id === $c->id; @endphp
                <button type="button" wire:click="setClass({{ $c->id }})"
                        style="position:relative; border-radius:12px; padding:12px 10px; text-align:left; transition:all 0.2s; cursor:pointer;
                            {{ $active
                                ? 'border:2px solid #4f46e5; background:linear-gradient(135deg,#eef2ff,#eff6ff); box-shadow:0 0 0 3px rgba(79,70,229,0.15);'
                                : 'border:2px solid rgba(229,231,235,0.8); background:white; box-shadow:0 1px 2px rgba(0,0,0,0.04);' }}">
                    <div style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:{{ $active ? '#4f46e5' : '#9ca3af' }};">Kelas</div>
                    <div style="font-size:18px; font-weight:800; color:{{ $active ? '#312e81' : '#1f2937' }}; line-height:1.2; margin-top:2px;">{{ $c->name }}</div>
                    <div style="font-size:9px; color:#9ca3af; margin-top:2px;">{{ $c->academic_year }}</div>
                    @if($active)
                        <div style="position:absolute; top:8px; right:8px; width:18px; height:18px; border-radius:9999px; background:#4f46e5; color:white; display:flex; align-items:center; justify-content:center;">
                            <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- STEP 2: PILIH SISWA --}}
    @if($classStudents->isNotEmpty())
        <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:rgba(255,255,255,0.9); padding:20px 22px; box-shadow:0 1px 3px 0 rgba(0,0,0,0.05);">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid rgba(229,231,235,0.6); flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:9999px; background:linear-gradient(135deg,#4f46e5,#2563eb); color:white; font-weight:800; font-size:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 2px 6px rgba(79,70,229,0.4);">2</div>
                    <div>
                        <div style="font-size:13px; font-weight:800; color:#1f2937;">Pilih Siswa</div>
                        <div style="font-size:11px; color:#6b7280; margin-top:1px;">{{ $selectedCount }} dari {{ $totalInClass }} siswa terpilih.</div>
                    </div>
                </div>
                <div>
                    @if($allSelected)
                        <button type="button" wire:click="clearSelection"
                                style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:9px; border:1px solid #d1d5db; background:white; color:#374151; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.2s; box-shadow:0 1px 2px rgba(0,0,0,0.05);"
                                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Kosongkan
                        </button>
                    @else
                        <button type="button" wire:click="selectAll"
                                style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:9px; border:none; background:linear-gradient(135deg,#4f46e5,#2563eb); color:white; font-size:12px; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,0.35); transition:all 0.2s;"
                                onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Pilih Semua
                        </button>
                    @endif
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(155px, 1fr)); gap:10px;">
                @foreach($classStudents as $s)
                    @php $isSel = in_array($s->id, $student_ids, true); @endphp
                    <button type="button" wire:click="toggleStudent({{ $s->id }})"
                            style="position:relative; border-radius:12px; padding:12px; text-align:left; cursor:pointer; transition:all 0.2s;
                                {{ $isSel
                                    ? 'border:2px solid #4f46e5; background:linear-gradient(135deg,#eef2ff,#fff); box-shadow:0 0 0 3px rgba(79,70,229,0.1);'
                                    : 'border:2px solid rgba(229,231,235,0.8); background:white; box-shadow:0 1px 2px rgba(0,0,0,0.04);' }}">
                        <div style="display:flex; align-items:center; gap:10px;">
                            @if($s->photo_url)
                                <img src="{{ $s->photo_url }}" style="width:38px; height:38px; border-radius:9999px; object-fit:cover; border:2px solid white; box-shadow:0 2px 4px rgba(0,0,0,0.1); flex-shrink:0;">
                            @else
                                <div style="width:38px; height:38px; border-radius:9999px; background:linear-gradient(135deg,#4f46e5,#2563eb); color:white; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:15px; flex-shrink:0; box-shadow:0 2px 4px rgba(0,0,0,0.1);">{{ mb_substr($s->name, 0, 1) }}</div>
                            @endif
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:12px; font-weight:700; color:#1f2937; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $s->name }}</div>
                                <div style="font-size:10px; color:#9ca3af; font-family:ui-monospace,monospace; margin-top:1px;">{{ $s->nis }}</div>
                            </div>
                        </div>
                        @if($isSel)
                            <div style="position:absolute; top:7px; right:7px; width:18px; height:18px; border-radius:9999px; background:#4f46e5; color:white; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 4px rgba(79,70,229,0.4);">
                                <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    @else
        <div style="border-radius:16px; border:2px dashed rgba(209,213,219,0.8); padding:48px 24px; text-align:center; color:#9ca3af;">
            <svg style="width:40px;height:40px;margin:0 auto 12px;opacity:0.4;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <div style="font-size:14px; font-weight:600;">Pilih kelas di atas untuk menampilkan daftar siswa.</div>
        </div>
    @endif

    {{-- STEP 3: PRATINJAU & UNDUH --}}
    @if($selectedCount > 0)
        <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:rgba(255,255,255,0.9); padding:20px 22px; box-shadow:0 1px 3px 0 rgba(0,0,0,0.05);">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid rgba(229,231,235,0.6); flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:9999px; background:linear-gradient(135deg,#10b981,#0d9488); color:white; font-weight:800; font-size:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 2px 6px rgba(16,185,129,0.4);">3</div>
                    <div>
                        <div style="font-size:13px; font-weight:800; color:#1f2937;">Pratinjau & Cetak</div>
                        <div style="font-size:11px; color:#6b7280; margin-top:1px;">{{ $selectedCount }} kartu siap dicetak. Format A4, 2 kartu per baris.</div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    @unless($showPreview)
                        <button type="button" wire:click="preview"
                                style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:10px; border:1.5px solid #6366f1; background:white; color:#4f46e5; font-size:13px; font-weight:700; cursor:pointer; transition:all 0.2s; box-shadow:0 1px 3px rgba(0,0,0,0.05);"
                                onmouseover="this.style.background='#eef2ff'" onmouseout="this.style.background='white'">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Pratinjau
                        </button>
                    @endunless
                    <button type="button" wire:click="downloadPdf" wire:loading.attr="disabled"
                            style="display:inline-flex; align-items:center; gap:7px; padding:9px 20px; border-radius:10px; border:none; background:linear-gradient(135deg,#059669,#0d9488); color:white; font-size:13px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(5,150,105,0.4); transition:all 0.2s;"
                            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(5,150,105,0.5)'"
                            onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(5,150,105,0.4)'">
                        <svg wire:loading.remove wire:target="downloadPdf" style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <svg wire:loading wire:target="downloadPdf" style="width:14px;height:14px;animation:spin 1s linear infinite;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span wire:loading.remove wire:target="downloadPdf">Unduh PDF</span>
                        <span wire:loading wire:target="downloadPdf">Membuat PDF...</span>
                    </button>
                </div>
            </div>

            @if($showPreview && $selected->isNotEmpty())
                <div style="border-radius:12px; background:linear-gradient(135deg,#f1f5f9,#e2e8f0); padding:20px;">
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(380px, 1fr)); gap:16px;">
                        @foreach($selected as $s)
                            @php
                                if (! $s->qr_token) { $s->generateQrToken(); }
                                $qr = \App\Filament\Pages\CetakKartuSiswa::makeQrDataUri($s->qr_token);
                            @endphp
                            {{-- CR80 ratio 85:54 --}}
                            <div style="position:relative; aspect-ratio:85/54; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px -10px rgba(0,0,0,0.3), 0 0 0 1px rgba(0,0,0,0.07); background:linear-gradient(135deg,#3730a3 0%,#1d4ed8 50%,#0e7490 100%); color:white;">
                                <div style="position:absolute; right:-48px; top:-48px; width:180px; height:180px; border-radius:9999px; background:rgba(255,255,255,0.08); filter:blur(30px);"></div>
                                <div style="position:absolute; left:-32px; bottom:-32px; width:160px; height:160px; border-radius:9999px; background:rgba(6,182,212,0.15); filter:blur(30px);"></div>
                                <div style="position:absolute; bottom:0; left:0; right:0; height:5px; background:linear-gradient(90deg,#f59e0b,#f43f5e,#ec4899);"></div>

                                <div style="position:relative; height:100%; display:flex; flex-direction:column; padding:14px 16px 18px;">
                                    <div style="display:flex; align-items:center; gap:8px; padding-bottom:9px; border-bottom:1px solid rgba(255,255,255,0.2); margin-bottom:10px;">
                                        @if($settings->logo)
                                            <img src="{{ asset('storage/'.$settings->logo) }}" style="width:26px;height:26px;border-radius:5px;background:rgba(255,255,255,0.9);padding:2px;">
                                        @else
                                            <div style="width:26px;height:26px;border-radius:5px;background:rgba(255,255,255,0.9);color:#3730a3;font-weight:900;font-size:12px;display:flex;align-items:center;justify-content:center;">S</div>
                                        @endif
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:8px;text-transform:uppercase;letter-spacing:0.12em;opacity:0.7;line-height:1;">Kartu Pelajar</div>
                                            <div style="font-size:11px;font-weight:800;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;">{{ $settings->school_name }}</div>
                                        </div>
                                    </div>

                                    <div style="display:flex; gap:10px; flex:1; min-height:0;">
                                        {{-- FOTO --}}
                                        @if($s->photo_url)
                                            <img src="{{ $s->photo_url }}" style="width:70px;height:86px;border-radius:8px;object-fit:cover;border:2px solid rgba(255,255,255,0.5);box-shadow:0 4px 12px rgba(0,0,0,0.25);flex-shrink:0;align-self:center;">
                                        @else
                                            <div style="width:70px;height:86px;border-radius:8px;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.4);display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:800;flex-shrink:0;align-self:center;">{{ mb_substr($s->name, 0, 1) }}</div>
                                        @endif

                                        {{-- INFO --}}
                                        <div style="flex:1;min-width:0;display:flex;flex-direction:column;justify-content:center;gap:2px;">
                                            <div style="font-size:13px;font-weight:800;letter-spacing:-0.01em;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px;">{{ $s->name }}</div>
                                            <div style="display:grid;grid-template-columns:48px 1fr;gap:0 6px;font-size:8.5px;line-height:1.55;">
                                                <span style="opacity:0.65;font-weight:600;">NIS</span>
                                                <span style="font-weight:700;font-family:ui-monospace,monospace;">{{ $s->nis ?? '—' }}</span>
                                                <span style="opacity:0.65;font-weight:600;">NISN</span>
                                                <span style="font-family:ui-monospace,monospace;">{{ $s->nisn ?? '—' }}</span>
                                                <span style="opacity:0.65;font-weight:600;">TTL</span>
                                                <span style="font-size:8px;">{{ $s->birth_place ? $s->birth_place.', ' : '' }}{{ $s->birth_date ? $s->birth_date->translatedFormat('d M Y') : '—' }}</span>
                                                <span style="opacity:0.65;font-weight:600;">Alamat</span>
                                                <span style="font-size:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $s->address ?? '—' }}</span>
                                            </div>
                                        </div>

                                        {{-- QR --}}
                                        <div style="text-align:center;flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                            <div style="background:white;padding:4px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.18);">
                                                <img src="{{ $qr }}" style="width:60px;height:60px;display:block;">
                                            </div>
                                            <div style="font-size:6px;margin-top:3px;opacity:0.65;font-family:ui-monospace,monospace;letter-spacing:0.04em;">{{ $s->qr_token }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div style="border-radius:12px; border:2px dashed rgba(199,210,254,0.8); padding:36px 24px; text-align:center; background:rgba(238,242,255,0.4);">
                    <svg style="width:36px;height:36px;margin:0 auto 10px;color:#a5b4fc;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <div style="font-size:13px;color:#6b7280;">Klik <strong style="color:#4f46e5;">Pratinjau</strong> untuk melihat tampilan kartu, atau langsung <strong style="color:#059669;">Unduh PDF</strong>.</div>
                </div>
            @endif
        </div>
    @endif

    {{-- TIPS --}}
    <div style="border-radius:16px; background:linear-gradient(135deg,#fffbeb,#fff7ed); border:1px solid rgba(251,191,36,0.3); padding:18px 20px; box-shadow:0 1px 3px 0 rgba(0,0,0,0.04);">
        <div style="display:flex; align-items:flex-start; gap:12px;">
            <div style="width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg,#f59e0b,#d97706); color:white; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 2px 6px rgba(245,158,11,0.35);">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div style="font-size:12px; font-weight:800; color:#92400e; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em;">Tips Pencetakan</div>
                <div style="font-size:12px; color:#78350f; line-height:1.7;">
                    <div style="display:flex;align-items:flex-start;gap:6px;margin-bottom:4px;"><span style="color:#f59e0b;font-weight:800;flex-shrink:0;">✓</span><span>Gunakan kertas <strong>A4 art paper 230gsm</strong> atau <strong>PVC</strong> untuk hasil terbaik.</span></div>
                    <div style="display:flex;align-items:flex-start;gap:6px;margin-bottom:4px;"><span style="color:#f59e0b;font-weight:800;flex-shrink:0;">✓</span><span>Setelah cetak, potong sesuai ukuran <strong>kartu kredit (85 × 54 mm)</strong>, lalu laminating.</span></div>
                    <div style="display:flex;align-items:flex-start;gap:6px;margin-bottom:4px;"><span style="color:#f59e0b;font-weight:800;flex-shrink:0;">✓</span><span>Pastikan QR tetap kontras tinggi — jangan diberi efek agar bisa di-scan.</span></div>
                    <div style="display:flex;align-items:flex-start;gap:6px;"><span style="color:#f59e0b;font-weight:800;flex-shrink:0;">✓</span><span>Token QR dapat di-regenerate kapan saja di menu <strong>Akademik › Siswa</strong> bila kartu hilang.</span></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</x-filament-panels::page>
