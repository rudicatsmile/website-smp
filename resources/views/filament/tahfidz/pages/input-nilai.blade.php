<x-filament-panels::page>
    {{-- HERO --}}
    <div style="position:relative;overflow:hidden;border-radius:16px;padding:24px 28px;color:white;background:linear-gradient(135deg,#4338ca 0%,#6366f1 50%,#818cf8 100%);box-shadow:0 10px 25px -5px rgba(0,0,0,0.15);">
        <div style="position:absolute;right:-40px;top:-40px;width:200px;height:200px;border-radius:9999px;background:rgba(255,255,255,0.08);filter:blur(40px);"></div>
        <div style="position:relative;display:flex;align-items:center;gap:16px;">
            <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,0.3);">
                <svg style="width:28px;height:28px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
            </div>
            <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.1em;opacity:0.75;font-weight:700;">Sahabat Qur'an</div>
                <h2 style="font-size:24px;font-weight:800;line-height:1.2;margin-top:4px;">Input Nilai Tahfidz</h2>
                <p style="font-size:13px;opacity:0.85;margin-top:4px;">Pilih kelas, tentukan surah, lalu isi nilai seluruh peserta sekaligus.</p>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div style="border-radius:14px;border:1px solid rgba(229,231,235,0.7);background:white;padding:20px 22px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Kelas</label>
                <select wire:model.live="school_class_id" wire:change="loadStudents"
                        style="width:100%;border:1.5px solid #d1d5db;border-radius:9px;padding:9px 12px;font-size:13px;color:#1f2937;background:white;outline:none;">
                    <option value="">— Pilih Kelas —</option>
                    @foreach($this->getClasses() as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;">Guru Pengampuh</label>
                <select wire:model="teacher_id"
                        style="width:100%;border:1.5px solid #d1d5db;border-radius:9px;padding:9px 12px;font-size:13px;color:#1f2937;background:white;outline:none;">
                    <option value="">— Pilih Guru —</option>
                    @foreach($this->getTeachers() as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    @if(count($rows) > 0)
        <div style="border-radius:14px;border:1px solid rgba(229,231,235,0.7);background:white;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:16px 22px;border-bottom:1px solid rgba(229,231,235,0.7);display:flex;align-items:center;justify-content:space-between;">
                <div style="font-size:13px;font-weight:800;color:#1f2937;">{{ count($rows) }} Peserta</div>
                <button type="button" wire:click="save"
                        style="display:inline-flex;align-items:center;gap:7px;padding:9px 20px;border-radius:10px;border:none;background:linear-gradient(135deg,#4338ca,#6366f1);color:white;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(67,56,202,0.4);">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Simpan Semua
                </button>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">#</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">Nama Siswa</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">NIS</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">Surah</th>
                            <th style="padding:11px 16px;text-align:center;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">Nilai (0-100)</th>
                            <th style="padding:11px 16px;text-align:left;font-weight:700;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #f1f5f9;">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $i => $row)
                            <tr style="background:{{ $i % 2 === 0 ? 'white' : '#f8fafc' }};border-bottom:1px solid #f1f5f9;">
                                <td style="padding:10px 16px;color:#9ca3af;font-size:12px;">{{ $i + 1 }}</td>
                                <td style="padding:10px 16px;font-weight:600;color:#1f2937;">{{ $row['name'] }}</td>
                                <td style="padding:10px 16px;font-family:ui-monospace,monospace;font-size:12px;color:#6b7280;">{{ $row['nis'] }}</td>
                                <td style="padding:6px 16px;">
                                    <input type="text" wire:model="rows.{{ $i }}.surah"
                                           placeholder="Mis: Al-Fatihah"
                                           style="width:140px;border:1.5px solid #e5e7eb;border-radius:7px;padding:6px 10px;font-size:12px;color:#1f2937;outline:none;"
                                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
                                </td>
                                <td style="padding:6px 16px;text-align:center;">
                                    <input type="number" wire:model="rows.{{ $i }}.score"
                                           min="0" max="100" placeholder="—"
                                           style="width:68px;border:1.5px solid #e5e7eb;border-radius:7px;padding:6px 8px;font-size:13px;font-weight:700;text-align:center;color:#1f2937;outline:none;"
                                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
                                </td>
                                <td style="padding:6px 16px;">
                                    <input type="text" wire:model="rows.{{ $i }}.description"
                                           placeholder="Catatan (opsional)"
                                           style="width:180px;border:1.5px solid #e5e7eb;border-radius:7px;padding:6px 10px;font-size:12px;color:#1f2937;outline:none;"
                                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($school_class_id)
        <div style="border-radius:14px;border:2px dashed rgba(209,213,219,0.8);padding:48px 24px;text-align:center;color:#9ca3af;">
            <svg style="width:40px;height:40px;margin:0 auto 12px;opacity:0.4;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            <div style="font-size:14px;font-weight:600;">Tidak ada peserta terdaftar di kelas ini.</div>
            <div style="font-size:12px;margin-top:4px;">Daftarkan siswa terlebih dahulu melalui menu Enrollment.</div>
        </div>
    @else
        <div style="border-radius:14px;border:2px dashed rgba(209,213,219,0.8);padding:48px 24px;text-align:center;color:#9ca3af;">
            <div style="font-size:14px;font-weight:600;">Pilih kelas untuk menampilkan daftar peserta.</div>
        </div>
    @endif
</x-filament-panels::page>
