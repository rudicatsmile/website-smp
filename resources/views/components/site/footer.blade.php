@props(['settings'])
<footer class="relative mt-20 bg-gradient-to-br from-slate-900 via-slate-900 to-emerald-950 text-slate-300 overflow-hidden">
    {{-- Decorative top accent --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-400 to-emerald-500"></div>

    {{-- Decorative pattern --}}
    <div class="absolute inset-0 opacity-[0.04] pointer-events-none" aria-hidden="true"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-teal-500/10 blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-12 gap-10">
            {{-- Brand & description --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3">
                    @if($settings->logo)
                        <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" class="h-12 w-12 object-contain rounded-lg bg-white/5 p-1 ring-1 ring-white/10">
                    @else
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 text-white grid place-items-center font-bold text-lg shadow-lg shadow-emerald-500/20">A9</div>
                    @endif
                    <div>
                        <h3 class="font-bold text-white text-lg leading-tight">{{ $settings->school_name }}</h3>
                        @if($settings->tagline)
                            <p class="text-xs text-emerald-300/90 mt-0.5">{{ $settings->tagline }}</p>
                        @endif
                    </div>
                </div>

                @if($settings->footer_text)
                    <p class="text-sm text-slate-400 mt-5 leading-relaxed max-w-md">{{ $settings->footer_text }}</p>
                @endif

                {{-- Social media --}}
                @if($settings->facebook || $settings->instagram || $settings->youtube || $settings->tiktok)
                    <div class="mt-6">
                        <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-3">Ikuti Kami</p>
                        <div class="flex items-center gap-2">
                            @if($settings->facebook)
                                <a href="{{ $settings->facebook }}" target="_blank" rel="noopener"
                                   class="w-9 h-9 rounded-lg bg-white/5 hover:bg-emerald-500 ring-1 ring-white/10 hover:ring-emerald-400 grid place-items-center text-slate-300 hover:text-white transition"
                                   aria-label="Facebook">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                            @if($settings->instagram)
                                <a href="{{ $settings->instagram }}" target="_blank" rel="noopener"
                                   class="w-9 h-9 rounded-lg bg-white/5 hover:bg-gradient-to-br hover:from-pink-500 hover:via-red-500 hover:to-yellow-500 ring-1 ring-white/10 grid place-items-center text-slate-300 hover:text-white transition"
                                   aria-label="Instagram">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                            @endif
                            @if($settings->youtube)
                                <a href="{{ $settings->youtube }}" target="_blank" rel="noopener"
                                   class="w-9 h-9 rounded-lg bg-white/5 hover:bg-red-600 ring-1 ring-white/10 hover:ring-red-500 grid place-items-center text-slate-300 hover:text-white transition"
                                   aria-label="YouTube">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </a>
                            @endif
                            @if($settings->tiktok)
                                <a href="{{ $settings->tiktok }}" target="_blank" rel="noopener"
                                   class="w-9 h-9 rounded-lg bg-white/5 hover:bg-slate-100 ring-1 ring-white/10 grid place-items-center text-slate-300 hover:text-slate-900 transition"
                                   aria-label="TikTok">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84-.1z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="lg:col-span-3">
                <h4 class="font-semibold text-white text-sm uppercase tracking-wider">Tautan Cepat</h4>
                <div class="mt-1 h-0.5 w-10 bg-gradient-to-r from-emerald-500 to-teal-400 rounded"></div>
                <ul class="mt-5 space-y-2.5 text-sm">
                    <li><a href="{{ route('profil') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-emerald-400 hover:translate-x-1 transition group"><span class="text-emerald-500 opacity-0 group-hover:opacity-100 transition">›</span> Profil Sekolah</a></li>
                    <li><a href="{{ route('akademik.index') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-emerald-400 hover:translate-x-1 transition group"><span class="text-emerald-500 opacity-0 group-hover:opacity-100 transition">›</span> Akademik</a></li>
                    <li><a href="{{ route('fasilitas.index') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-emerald-400 hover:translate-x-1 transition group"><span class="text-emerald-500 opacity-0 group-hover:opacity-100 transition">›</span> Fasilitas</a></li>
                    <li><a href="{{ route('prestasi.index') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-emerald-400 hover:translate-x-1 transition group"><span class="text-emerald-500 opacity-0 group-hover:opacity-100 transition">›</span> Prestasi</a></li>
                    <li><a href="{{ route('berita.index') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-emerald-400 hover:translate-x-1 transition group"><span class="text-emerald-500 opacity-0 group-hover:opacity-100 transition">›</span> Berita</a></li>
                    <li><a href="{{ route('spmb.index') }}" class="inline-flex items-center gap-1 text-emerald-300 font-semibold hover:text-emerald-200 hover:translate-x-1 transition group"><span class="text-emerald-400">›</span> Penerimaan Siswa Baru</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-4">
                <h4 class="font-semibold text-white text-sm uppercase tracking-wider">Kontak</h4>
                <div class="mt-1 h-0.5 w-10 bg-gradient-to-r from-emerald-500 to-teal-400 rounded"></div>
                <ul class="mt-5 space-y-3 text-sm">
                    @if($settings->address)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-500/10 ring-1 ring-emerald-500/20 grid place-items-center text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="text-slate-400 leading-relaxed pt-1">{{ $settings->address }}</span>
                        </li>
                    @endif
                    @if($settings->phone)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-500/10 ring-1 ring-emerald-500/20 grid place-items-center text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <a href="tel:{{ $settings->phone }}" class="text-slate-400 hover:text-emerald-400 transition pt-1">{{ $settings->phone }}</a>
                        </li>
                    @endif
                    @if($settings->whatsapp)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-500/10 ring-1 ring-emerald-500/20 grid place-items-center text-emerald-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </div>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->whatsapp) }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-emerald-400 transition pt-1">{{ $settings->whatsapp }}</a>
                        </li>
                    @endif
                    @if($settings->email)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-8 h-8 rounded-lg bg-emerald-500/10 ring-1 ring-emerald-500/20 grid place-items-center text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <a href="mailto:{{ $settings->email }}" class="text-slate-400 hover:text-emerald-400 transition pt-1 break-all">{{ $settings->email }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                {{ $settings->copyright ?? '© '.date('Y').' '.$settings->school_name.'. All rights reserved.' }}
            </p>
            <div class="flex items-center gap-4 text-xs text-slate-500">
                <a href="{{ route('kontak') }}" class="hover:text-emerald-400 transition">Kontak</a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('download.index') }}" class="hover:text-emerald-400 transition">Download</a>
                <span class="text-slate-700">•</span>
                <span class="inline-flex items-center gap-1">
                    Made with <span class="text-rose-500">♥</span> for education
                </span>
            </div>
        </div>
    </div>
</footer>
