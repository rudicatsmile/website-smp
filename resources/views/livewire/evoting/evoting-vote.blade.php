<div style="min-height: 100vh; background-color: #f8fafc; padding: 1rem; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <div style="max-width: 48rem; margin: 0 auto;">
        
        <!-- HERO HEADER (Inline Style seperti absensi-hari-ini) -->
        <div style="position:relative; overflow:hidden; border-radius:16px; padding:20px 24px; color:white; background:linear-gradient(135deg, #4f46e5 0%, #3b82f6 50%, #0ea5e9 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1); margin-bottom: 24px; text-align: center;">
            <!-- Glow Effects -->
            <div style="position:absolute; right:-48px; top:-48px; width:224px; height:224px; border-radius:9999px; background:rgba(255,255,255,0.15); filter:blur(48px);"></div>
            <div style="position:absolute; left:-32px; bottom:-32px; width:192px; height:192px; border-radius:9999px; background:rgba(255,255,255,0.15); filter:blur(48px);"></div>
            <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(255,255,255,0.05), transparent);"></div>

            <div style="position:relative; z-index: 10;">
                <h1 style="font-size:28px; font-weight:900; letter-spacing:-0.025em; line-height:1.2; text-shadow: 0 2px 8px rgba(0,0,0,0.15); margin: 0;">
                    {{ $election->title }}
                </h1>
                <p style="font-size:14px; opacity:0.9; margin-top:8px; font-weight: 500;">
                    Pilihlah kandidat terbaik menurut Anda. Suara Anda menentukan masa depan sekolah!
                </p>
                <div style="display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:9999px; background:rgba(255,255,255,0.2); font-size:10px; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-top: 14px; backdrop-filter: blur(4px); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <span style="width:6px;height:6px;border-radius:9999px;background:#67e8f9;display:inline-block;box-shadow: 0 0 6px #67e8f9;"></span>
                    E-Voting System
                </div>
            </div>
        </div>

        <!-- DAFTAR KANDIDAT -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            @foreach($candidates as $candidate)
            <div style="border-radius:20px; border:1px solid rgba(229,231,235,0.8); background:rgba(255,255,255,0.95); box-shadow:0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.025); display:flex; flex-direction:column; overflow:hidden; transition: transform 0.2s, box-shadow 0.2s;">
                
                <!-- Foto & Badge -->
                <div style="position:relative; width:100%; padding-top:100%; background-color:#f1f5f9;">
                    @if($candidate->photo)
                        <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="position:absolute; top:0; left:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                            <svg style="width:80px; height:80px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    @endif
                    
                    <!-- Badge Nomor Urut -->
                    <div style="position:absolute; top:16px; left:16px; width:50px; height:50px; border-radius:9999px; background:linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:900; border:3px solid white; box-shadow:0 4px 12px rgba(79, 70, 229, 0.5);">
                        {{ $candidate->candidate_number }}
                    </div>
                </div>

                <!-- Konten Info -->
                <div style="padding:16px; display:flex; flex-direction:column; flex:1;">
                    <div style="margin-bottom:12px;">
                        <h3 style="font-size:22px; font-weight:900; color:#0f172a; line-height:1.2; letter-spacing:-0.02em; margin:0;">
                            {{ $candidate->name }}
                        </h3>
                    </div>
                    
                    @if($candidate->tagline)
                        <div style="margin-bottom:12px; padding:12px; border-radius:10px; background:linear-gradient(135deg, rgba(79,70,229,0.05), rgba(59,130,246,0.05)); border-left:4px solid #4f46e5; position:relative; overflow:hidden;">
                            <svg style="position:absolute; bottom:-8px; right:-8px; width:48px; height:48px; color:rgba(79,70,229,0.1); transform:rotate(-10deg);" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" /></svg>
                            <p style="margin:0; font-size:13px; font-weight:700; color:#4338ca; font-style:italic; position:relative; z-index:10; line-height:1.4;">
                                "{{ $candidate->tagline }}"
                            </p>
                        </div>
                    @endif

                    <div style="margin-bottom:12px; flex:1;">
                        @if($candidate->vision)
                        <div>
                            <span style="display:block; font-size:12px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Visi:</span>
                            <div style="font-size:14px; color:#475569; line-height:1.6; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                                {!! $candidate->vision !!}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Tombol Pilih -->
                    <div style="margin-top:auto; padding-top:16px; border-top:1px solid #e2e8f0;">
                        <button wire:click="confirmVote({{ $candidate->id }})" 
                                style="width:100%; display:inline-flex; align-items:center; justify-content:center; padding:14px 20px; border-radius:12px; font-size:15px; font-weight:800; color:white; background:linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border:none; cursor:pointer; box-shadow:0 6px 15px -3px rgba(79, 70, 229, 0.4); transition:all 0.2s;"
                                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px -4px rgba(79, 70, 229, 0.5)';"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 6px 15px -3px rgba(79, 70, 229, 0.4)';">
                            <span style="letter-spacing:0.02em;">Pilih Kandidat No. {{ $candidate->candidate_number }}</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- MODAL KONFIRMASI -->
    @if($showConfirmModal)
    <div style="position:fixed; top:0; left:0; right:0; bottom:0; z-index:50; display:flex; align-items:center; justify-content:center; padding:16px;">
        <!-- Overlay Gelap -->
        <div wire:click="cancelVote" style="position:absolute; inset:0; background-color:rgba(15,23,42,0.8); backdrop-filter:blur(4px);"></div>

        <!-- Panel Modal -->
        <div style="position:relative; z-index:10; background:white; width:100%; max-width:400px; border-radius:24px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); border:1px solid rgba(255,255,255,0.2);">
            
            <div style="padding:32px 24px 24px; text-align:center;">
                <!-- Icon Konfirmasi -->
                <div style="width:64px; height:64px; margin:0 auto 20px; border-radius:16px; background:linear-gradient(135deg, rgba(79,70,229,0.1), rgba(59,130,246,0.1)); display:flex; align-items:center; justify-content:center; color:#4f46e5; border:1px solid rgba(79,70,229,0.2);">
                    <svg style="width:32px; height:32px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                
                <h3 style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 8px;">Konfirmasi Pilihan</h3>
                <p style="font-size:14px; color:#64748b; margin:0 0 24px;">Anda akan memberikan suara rahasia untuk:</p>
                
                <!-- Info Kandidat yang Dipilih (Jarak Maksimal Sesuai Permintaan) -->
                <div style="padding:30px 20px; border-radius:16px; background:linear-gradient(135deg, #f8fafc, #f1f5f9); border:1px solid #e2e8f0; box-shadow:inset 0 2px 4px rgba(0,0,0,0.02);">
                    <p style="margin:0 0 20px; font-size:12px; font-weight:800; color:#4f46e5; text-transform:uppercase; letter-spacing:0.15em;">
                        Kandidat No. {{ $selectedCandidateNumber }}
                    </p>
                    <p style="margin:0; font-size:32px; font-weight:900; line-height:1.2; background:linear-gradient(135deg, #312e81 0%, #4f46e5 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
                        {{ $selectedCandidateName }}
                    </p>
                </div>

                <p style="margin:24px 0 0; font-size:13px; font-weight:600; color:#ef4444; background:rgba(239,68,68,0.1); padding:10px; border-radius:8px;">
                    Pilihan yang sudah disubmit tidak dapat diubah kembali.
                </p>
            </div>

            <!-- Tombol Aksi Modal -->
            <div style="background-color:#f8fafc; padding:20px 24px; border-top:1px solid #e2e8f0; display:flex; flex-direction:column; gap:12px;">
                <button wire:click="submitVote" type="button" 
                        style="width:100%; padding:14px; border-radius:12px; font-size:15px; font-weight:800; color:white; background:linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border:none; cursor:pointer; box-shadow:0 4px 12px rgba(79, 70, 229, 0.3); transition:all 0.2s;"
                        onmouseover="this.style.boxShadow='0 6px 16px rgba(79, 70, 229, 0.4)'; this.style.transform='translateY(-1px)';"
                        onmouseout="this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.3)'; this.style.transform='translateY(0)';">
                    Ya, Yakin & Kirim Suara
                </button>
                <button wire:click="cancelVote" type="button" 
                        style="width:100%; padding:14px; border-radius:12px; font-size:15px; font-weight:700; color:#475569; background:white; border:1px solid #cbd5e1; cursor:pointer; box-shadow:0 1px 2px rgba(0,0,0,0.05); transition:all 0.2s;"
                        onmouseover="this.style.backgroundColor='#f1f5f9';"
                        onmouseout="this.style.backgroundColor='white';">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
