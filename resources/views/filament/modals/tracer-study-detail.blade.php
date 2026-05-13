@php
    $statusColors = [
        'working'      => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Bekerja'],
        'studying'     => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Kuliah'],
        'entrepreneur' => ['bg' => '#fef9c3', 'text' => '#854d0e', 'label' => 'Wirausaha'],
        'both'         => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'label' => 'Kuliah & Bekerja'],
        'unemployed'   => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Belum Bekerja'],
        'other'        => ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => 'Lainnya'],
    ];
    $sc = $statusColors[$record->current_status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => 'Lainnya'];
    $initials = collect(explode(' ', $record->name))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
@endphp

<div style="font-family: system-ui, -apple-system, sans-serif; font-size: 14px;">

    {{-- Header banner --}}
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #0e7490 100%); border-radius: 12px; padding: 24px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: #fff; flex-shrink: 0; letter-spacing: 1px;">
            {{ $initials }}
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 18px; font-weight: 800; color: #fff; line-height: 1.2; margin-bottom: 4px;">{{ $record->name }}</div>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="background: rgba(255,255,255,0.15); color: #e2e8f0; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.2);">
                    Lulus {{ $record->graduation_year }}
                </span>
                <span style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 20px;">
                    {{ $sc['label'] }}
                </span>
                @if($record->allow_publish)
                    <span style="background: rgba(34,197,94,0.2); color: #86efac; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 20px; border: 1px solid rgba(34,197,94,0.3);">
                        ✓ Izin Publikasi
                    </span>
                @endif
            </div>
        </div>
        <div style="text-align: right; flex-shrink: 0;">
            <div style="color: rgba(255,255,255,0.5); font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">Dikirim</div>
            <div style="color: #e2e8f0; font-size: 12px; font-weight: 600;">{{ $record->created_at->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Info grid --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px;">

        @foreach([
            ['Email', $record->email ?? null, '✉'],
            ['No. HP', $record->phone ?? null, '📱'],
            ['Kota', $record->city ?? null, '📍'],
            ['Penghasilan', $record->income_range ? $record->income_label : null, '💰'],
        ] as [$label, $value, $icon])
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px;">
                <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; font-weight: 700; margin-bottom: 4px;">{{ $icon }} {{ $label }}</div>
                <div style="color: {{ $value ? '#1e293b' : '#cbd5e1' }}; font-weight: {{ $value ? '600' : '400' }}; font-size: 13px;">{{ $value ?? '—' }}</div>
            </div>
        @endforeach

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; grid-column: span 2;">
            <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; font-weight: 700; margin-bottom: 4px;">🏢 Perusahaan / Institusi</div>
            <div style="color: #1e293b; font-weight: 600; font-size: 13px;">
                {{ collect([$record->position, $record->company_or_institution])->filter()->implode(' — ') ?: '—' }}
            </div>
        </div>
    </div>

    {{-- Ratings --}}
    <div style="background: linear-gradient(135deg, #fefce8, #fef9c3); border: 1px solid #fde68a; border-radius: 10px; padding: 16px 18px; margin-bottom: 16px;">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #92400e; font-weight: 800; margin-bottom: 12px;">⭐ Penilaian Sekolah</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            @foreach([
                ['Relevansi Pendidikan', $record->school_relevance],
                ['Kualitas Pendidikan', $record->school_quality],
            ] as [$label, $score])
                <div>
                    <div style="font-size: 12px; color: #78350f; font-weight: 600; margin-bottom: 6px;">{{ $label }}</div>
                    @if($score)
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <div style="display: flex; gap: 2px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="font-size: 18px; color: {{ $i <= $score ? '#f59e0b' : '#d1d5db' }}; line-height: 1;">★</span>
                                @endfor
                            </div>
                            <span style="font-size: 13px; font-weight: 800; color: #92400e;">{{ $score }}<span style="font-weight: 400; color: #a16207;">/5</span></span>
                        </div>
                    @else
                        <span style="color: #d1d5db; font-style: italic;">Tidak diisi</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Suggestions --}}
    @if($record->suggestions)
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #22c55e; border-radius: 10px; padding: 16px 18px; margin-bottom: 16px;">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: #166534; font-weight: 800; margin-bottom: 8px;">💡 Saran untuk Sekolah</div>
            <div style="color: #14532d; font-size: 13px; line-height: 1.7;">{{ $record->suggestions }}</div>
        </div>
    @endif

    {{-- Status footer --}}
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 12px;">
        <div style="color: #64748b;">
            ID Respons: <span style="font-weight: 700; color: #334155;">#{{ $record->id }}</span>
        </div>
        @if($record->is_processed)
            <div style="background: #dcfce7; color: #166534; font-size: 11px; font-weight: 700; padding: 3px 12px; border-radius: 20px;">
                ✓ Sudah Diproses · {{ $record->processed_at?->format('d M Y') }}
            </div>
        @else
            <div style="background: #fef9c3; color: #854d0e; font-size: 11px; font-weight: 700; padding: 3px 12px; border-radius: 20px;">
                ⏳ Menunggu Diproses
            </div>
        @endif
    </div>

</div>
