@props(['settings'])
<footer class="bg-slate-900 text-slate-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid md:grid-cols-3 gap-8">
        <div>
            <h3 class="font-semibold text-white text-lg">{{ $settings->school_name }}</h3>
            @if($settings->tagline)<p class="text-sm text-slate-400 mt-1">{{ $settings->tagline }}</p>@endif
            @if($settings->footer_text)<p class="text-sm text-slate-300 mt-3">{{ $settings->footer_text }}</p>@endif
        </div>
        <div>
            <h4 class="font-semibold text-white">Kontak</h4>
            <ul class="text-sm space-y-1 mt-3 text-slate-300">
                @if($settings->address)<li>{{ $settings->address }}</li>@endif
                @if($settings->phone)<li>Telp: {{ $settings->phone }}</li>@endif
                @if($settings->email)<li>Email: {{ $settings->email }}</li>@endif
                @if($settings->whatsapp)<li>WA: {{ $settings->whatsapp }}</li>@endif
            </ul>
        </div>
        <div>
            <h4 class="font-semibold text-white">Tautan</h4>
            <ul class="text-sm space-y-1 mt-3 text-slate-300">
                <li><a href="{{ route('profil') }}" class="hover:text-white">Profil</a></li>
                <li><a href="{{ route('berita.index') }}" class="hover:text-white">Berita</a></li>
                <li><a href="{{ route('spmb.index') }}" class="hover:text-white">SPMB</a></li>
                <li><a href="{{ route('kontak') }}" class="hover:text-white">Kontak</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-slate-800 py-4 text-center text-xs text-slate-400">
        {{ $settings->copyright ?? '© '.date('Y').' '.$settings->school_name }}
    </div>
</footer>
