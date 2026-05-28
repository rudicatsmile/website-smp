<div style="max-height:500px;overflow-y:auto;padding:4px;">
    @if($activities->isEmpty())
        <div style="text-align:center;padding:40px 20px;color:#9ca3af;">
            <svg style="width:48px;height:48px;margin:0 auto 12px;opacity:0.4;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p style="font-size:14px;font-weight:600;">Belum ada aktivitas tercatat.</p>
        </div>
    @else
        <div style="position:relative;padding-left:24px;">
            {{-- Timeline line --}}
            <div style="position:absolute;left:11px;top:8px;bottom:8px;width:2px;background:linear-gradient(180deg,#6366f1,#a5b4fc,#e5e7eb);border-radius:99px;"></div>

            @foreach($activities as $activity)
                @php
                    $isCreated = str_contains($activity->description, 'created');
                    $isDeleted = str_contains($activity->description, 'deleted');
                    $dotColor = $isCreated ? '#10b981' : ($isDeleted ? '#ef4444' : '#6366f1');
                    $bgColor = $isCreated ? '#f0fdf4' : ($isDeleted ? '#fef2f2' : '#eef2ff');
                    $borderColor = $isCreated ? '#bbf7d0' : ($isDeleted ? '#fecaca' : '#c7d2fe');
                    $labelColor = $isCreated ? '#059669' : ($isDeleted ? '#dc2626' : '#4f46e5');
                    $label = match(true) {
                        str_contains($activity->description, 'created') && str_contains($activity->description, 'kasus') => '➕ Kasus Ditambahkan',
                        str_contains($activity->description, 'updated') && str_contains($activity->description, 'kasus') => '✏️ Kasus Diubah',
                        str_contains($activity->description, 'deleted') && str_contains($activity->description, 'kasus') => '🗑️ Kasus Dihapus',
                        str_contains($activity->description, 'created') && str_contains($activity->description, 'assessment') => '➕ Assessment Ditambahkan',
                        str_contains($activity->description, 'updated') && str_contains($activity->description, 'assessment') => '✏️ Assessment Diubah',
                        str_contains($activity->description, 'deleted') && str_contains($activity->description, 'assessment') => '🗑️ Assessment Dihapus',
                        str_contains($activity->description, 'created') => '✨ Sesi Dibuat',
                        str_contains($activity->description, 'updated') => '📝 Sesi Diperbarui',
                        str_contains($activity->description, 'deleted') => '🗑️ Sesi Dihapus',
                        default => '📌 ' . ucfirst($activity->description),
                    };
                @endphp

                <div style="position:relative;margin-bottom:16px;">
                    {{-- Timeline dot --}}
                    <div style="position:absolute;left:-19px;top:14px;width:12px;height:12px;border-radius:99px;background:{{ $dotColor }};border:3px solid white;box-shadow:0 0 0 2px {{ $dotColor }}33;"></div>

                    {{-- Card --}}
                    <div style="background:{{ $bgColor }};border:1px solid {{ $borderColor }};border-radius:10px;padding:12px 16px;transition:all 0.2s;">
                        {{-- Header --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                            <span style="font-size:12px;font-weight:700;color:{{ $labelColor }};">{{ $label }}</span>
                            <span style="font-size:11px;color:#9ca3af;font-weight:500;">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>

                        {{-- Meta --}}
                        <div style="display:flex;align-items:center;gap:12px;font-size:11px;color:#6b7280;margin-bottom:6px;">
                            <span style="display:inline-flex;align-items:center;gap:4px;">
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                                {{ $activity->causer?->name ?? 'Sistem' }}
                            </span>
                            <span style="display:inline-flex;align-items:center;gap:4px;">
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $activity->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        {{-- Changes --}}
                        @if($activity->properties->isNotEmpty())
                            @php
                                $old = $activity->properties->get('old', []);
                                $new = $activity->properties->get('attributes', []);
                            @endphp
                            @if(!empty($new))
                                <div style="margin-top:8px;padding-top:8px;border-top:1px dashed {{ $borderColor }};">
                                    @foreach($new as $key => $value)
                                        @php
                                            $oldVal = $old[$key] ?? null;
                                            $newVal = $value;
                                            if(is_array($oldVal)) $oldVal = implode(', ', $oldVal);
                                            if(is_array($newVal)) $newVal = implode(', ', $newVal);
                                            $fieldLabel = str_replace('_', ' ', ucfirst($key));
                                        @endphp
                                        @if($oldVal != $newVal)
                                            <div style="display:flex;align-items:center;gap:8px;font-size:11px;margin-bottom:4px;">
                                                <span style="font-weight:600;color:#374151;min-width:100px;">{{ $fieldLabel }}</span>
                                                @if($oldVal)
                                                    <span style="padding:2px 8px;border-radius:4px;background:#fee2e2;color:#991b1b;text-decoration:line-through;font-size:10px;">{{ \Illuminate\Support\Str::limit((string)$oldVal, 30) }}</span>
                                                @endif
                                                <svg style="width:10px;height:10px;color:#9ca3af;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                                <span style="padding:2px 8px;border-radius:4px;background:#dcfce7;color:#166534;font-size:10px;font-weight:600;">{{ \Illuminate\Support\Str::limit((string)$newVal, 30) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
