{{-- Milleneal skin: footer --}}
<footer class="relative mt-20 bg-gradient-to-br from-purple-900 via-purple-800 to-pink-800 text-white overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-pink-500/20 blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-cyan-400/15 blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-1/2 left-1/3 w-72 h-72 rounded-full bg-yellow-300/10 blur-3xl pointer-events-none" aria-hidden="true"></div>

    {{-- Sparkle dots --}}
    <div class="absolute inset-0 opacity-[0.06] pointer-events-none" aria-hidden="true"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-12 gap-10">
            {{-- Brand --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3">
                    @if($settings->logo)
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-pink-400 to-yellow-300 blur-md opacity-60 rounded-full"></div>
                            <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" class="relative h-14 w-14 object-contain rounded-full bg-white p-1.5 ring-2 ring-white/30">
                        </div>
                    @else
                        <div class="h-14 w-14 rounded-full bg-gradient-to-br from-pink-400 via-yellow-300 to-orange-400 text-purple-900 grid place-items-center font-black text-xl shadow-2xl">A9</div>
                    @endif
                    <div>
                        <h3 class="font-extrabold text-white text-xl leading-tight">{{ $settings->school_name }}</h3>
                        @if($settings->tagline)
                            <p class="text-sm text-pink-200 mt-0.5">✨ {{ $settings->tagline }}</p>
                        @endif
                    </div>
                </div>

                @if($settings->footer_text)
                    <p class="text-sm text-purple-100/90 mt-5 leading-relaxed max-w-md">{{ $settings->footer_text }}</p>
                @endif

                @if($settings->facebook || $settings->instagram || $settings->youtube || $settings->tiktok)
                    <div class="mt-6">
                        <p class="text-xs uppercase tracking-widest text-pink-300 font-bold mb-3">🌟 Follow Us</p>
                        <div class="flex items-center gap-2.5">
                            @if($settings->facebook)
                                <a href="{{ $settings->facebook }}" target="_blank" rel="noopener"
                                   class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-white hover:text-blue-600 backdrop-blur ring-1 ring-white/20 grid place-items-center text-white hover:scale-110 hover:-rotate-6 transition" aria-label="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                            @if($settings->instagram)
                                <a href="{{ $settings->instagram }}" target="_blank" rel="noopener"
                                   class="w-11 h-11 rounded-2xl bg-gradient-to-br from-pink-500 via-red-500 to-yellow-500 hover:shadow-xl hover:shadow-pink-500/40 grid place-items-center text-white hover:scale-110 hover:rotate-6 transition" aria-label="Instagram">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                            @endif
                            @if($settings->youtube)
                                <a href="{{ $settings->youtube }}" target="_blank" rel="noopener"
                                   class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-red-600 ring-1 ring-white/20 backdrop-blur grid place-items-center text-white hover:scale-110 hover:-rotate-6 transition" aria-label="YouTube">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </a>
                            @endif
                            @if($settings->tiktok)
                                <a href="{{ $settings->tiktok }}" target="_blank" rel="noopener"
                                   class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-white hover:text-slate-900 backdrop-blur ring-1 ring-white/20 grid place-items-center text-white hover:scale-110 hover:rotate-6 transition" aria-label="TikTok">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84-.1z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="lg:col-span-3">
                <h4 class="font-extrabold text-white text-base">Tautan 🔗</h4>
                <div class="mt-1 h-1 w-12 bg-gradient-to-r from-pink-400 to-yellow-300 rounded-full"></div>
                <ul class="mt-5 space-y-2.5 text-sm">
                    <li><a href="{{ route('profil') }}" class="inline-flex items-center gap-2 text-purple-100 hover:text-yellow-300 hover:translate-x-2 transition">→ Profil</a></li>
                    <li><a href="{{ route('akademik.index') }}" class="inline-flex items-center gap-2 text-purple-100 hover:text-yellow-300 hover:translate-x-2 transition">→ Akademik</a></li>
                    <li><a href="{{ route('fasilitas.index') }}" class="inline-flex items-center gap-2 text-purple-100 hover:text-yellow-300 hover:translate-x-2 transition">→ Fasilitas</a></li>
                    <li><a href="{{ route('prestasi.index') }}" class="inline-flex items-center gap-2 text-purple-100 hover:text-yellow-300 hover:translate-x-2 transition">→ Prestasi</a></li>
                    <li><a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-purple-100 hover:text-yellow-300 hover:translate-x-2 transition">→ Berita</a></li>
                    <li><a href="{{ route('spmb.index') }}" class="inline-flex items-center gap-2 text-yellow-300 font-bold hover:text-yellow-200 hover:translate-x-2 transition">🚀 PSB Online</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-4">
                <h4 class="font-extrabold text-white text-base">Hubungi Kami 💌</h4>
                <div class="mt-1 h-1 w-12 bg-gradient-to-r from-pink-400 to-yellow-300 rounded-full"></div>
                <ul class="mt-5 space-y-3 text-sm">
                    @if($settings->address)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-9 h-9 rounded-2xl bg-pink-400/20 ring-1 ring-pink-300/30 grid place-items-center text-pink-200">📍</div>
                            <span class="text-purple-100 leading-relaxed pt-1">{{ $settings->address }}</span>
                        </li>
                    @endif
                    @if($settings->phone)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-9 h-9 rounded-2xl bg-pink-400/20 ring-1 ring-pink-300/30 grid place-items-center text-pink-200">📞</div>
                            <a href="tel:{{ $settings->phone }}" class="text-purple-100 hover:text-yellow-300 transition pt-1">{{ $settings->phone }}</a>
                        </li>
                    @endif
                    @if($settings->whatsapp)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-9 h-9 rounded-2xl bg-pink-400/20 ring-1 ring-pink-300/30 grid place-items-center text-pink-200">💬</div>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->whatsapp) }}" target="_blank" rel="noopener" class="text-purple-100 hover:text-yellow-300 transition pt-1">{{ $settings->whatsapp }}</a>
                        </li>
                    @endif
                    @if($settings->email)
                        <li class="flex gap-3">
                            <div class="shrink-0 w-9 h-9 rounded-2xl bg-pink-400/20 ring-1 ring-pink-300/30 grid place-items-center text-pink-200">✉️</div>
                            <a href="mailto:{{ $settings->email }}" class="text-purple-100 hover:text-yellow-300 transition pt-1 break-all">{{ $settings->email }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-white/15 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-purple-200/80">
                {{ $settings->copyright ?? '© '.date('Y').' '.$settings->school_name.'. Made with ♥' }}
            </p>
            <div class="flex items-center gap-4 text-xs text-purple-200/80">
                <a href="{{ route('kontak') }}" class="hover:text-yellow-300 transition">Kontak</a>
                <span class="text-purple-400">•</span>
                <a href="{{ route('download.index') }}" class="hover:text-yellow-300 transition">Download</a>
                <span class="text-purple-400">•</span>
                <span>✨ Stay Awesome</span>
            </div>
        </div>
    </div>
</footer>
