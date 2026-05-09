@php
    use App\Models\Announcement;
    $announcements = Announcement::active()->get();
    if ($announcements->isEmpty()) return;
@endphp
@foreach ($announcements as $announcement)
    @php
        $colorClasses = match ($announcement->color) {
            'emerald' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
            'blue' => 'bg-blue-50 border-blue-200 text-blue-800',
            'amber' => 'bg-amber-50 border-amber-200 text-amber-800',
            'rose' => 'bg-rose-50 border-rose-200 text-rose-800',
            default => 'bg-slate-50 border-slate-200 text-slate-800',
        };
        $iconClasses = match ($announcement->color) {
            'emerald' => 'text-emerald-600',
            'blue' => 'text-blue-600',
            'amber' => 'text-amber-600',
            'rose' => 'text-rose-600',
            default => 'text-slate-600',
        };
    @endphp
    <div class="relative {{ $colorClasses }} border-b" data-announcement-id="{{ $announcement->id }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">
                            <span class="font-bold">{{ $announcement->title }}</span> — {{ $announcement->message }}
                        </p>
                    </div>
                </div>
                @if ($announcement->link_url)
                    <a href="{{ $announcement->link_url }}" class="flex-shrink-0 text-sm font-medium hover:underline {{ $iconClasses }}">
                        {{ $announcement->link_text }} →
                    </a>
                @endif
                @if ($announcement->is_dismissible)
                    <button onclick="this.closest('[data-announcement-id]').remove(); localStorage.setItem('announcement-dismissed-{{ $announcement->id }}', 'true');"
                            class="flex-shrink-0 p-1 rounded hover:bg-black/5 transition-colors"
                            aria-label="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @if ($announcement->is_dismissible)
        <script>
            (function() {
                const dismissed = localStorage.getItem('announcement-dismissed-{{ $announcement->id }}');
                if (dismissed === 'true') {
                    document.querySelector('[data-announcement-id="{{ $announcement->id }}"]').remove();
                }
            })();
        </script>
    @endif
@endforeach
