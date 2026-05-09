<div>
    <x-site.page-hero key="staff" title="Direktori Guru & Staf" subtitle="Kenali tim pendidik kami" icon="heroicon-o-users" />

    <x-site.page-frame>
        <div class="space-y-8">
        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Cari nama guru atau mata pelajaran..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
                    >
                </div>
                <div class="md:w-64">
                    <select
                        wire:model.live="categoryId"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Principal Featured -->
        @if($principal)
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-8 text-white mb-8">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="w-32 h-32 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border-4 border-white/30">
                        @if($principal->photo)
                            <img src="{{ $principal->photo_url }}" alt="{{ $principal->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl font-bold">{{ substr($principal->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="text-center md:text-left">
                        <div class="inline-block px-3 py-1 bg-white/20 rounded-full text-sm font-medium mb-2">Kepala Sekolah</div>
                        <h2 class="text-2xl font-bold mb-2">{{ $principal->name }}</h2>
                        <p class="text-white/90">{{ $principal->quote }}</p>
                    </div>
                    <a href="{{ route('staff.show', $principal->slug) }}" class="mt-4 md:mt-0 px-6 py-3 bg-white text-emerald-600 rounded-xl font-semibold hover:bg-white/90 transition">
                        Lihat Profil
                    </a>
                </div>
            </div>
        @endif

        <!-- Staff Grid -->
        @if($staff->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($staff as $member)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-100 group">
                        <div class="relative h-48 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                            @if($member->photo)
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-6xl font-bold text-slate-400">{{ substr($member->name, 0, 1) }}</span>
                            @endif
                            @if($member->category)
                                <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-slate-700">
                                    {{ $member->category->name }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-emerald-600 transition">{{ $member->name }}</h3>
                            @if($member->position)
                                <p class="text-sm text-slate-600 mb-3">{{ $member->position }}</p>
                            @endif
                            @if($member->subjects && count($member->subjects) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($member->subjects, 0, 3) as $subject)
                                        <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium">{{ is_array($subject) ? ($subject['subject'] ?? '') : $subject }}</span>
                                    @endforeach
                                    @if(count($member->subjects) > 3)
                                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium">+{{ count($member->subjects) - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                            <a href="{{ route('staff.show', $member->slug) }}" class="inline-flex items-center text-emerald-600 font-medium hover:text-emerald-700 transition">
                                Lihat Profil
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($staff->hasPages())
                <div class="flex justify-center">
                    {{ $staff->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">Tidak ada data ditemukan</h3>
                <p class="text-slate-500">Coba ubah kata kunci pencarian atau filter kategori</p>
            </div>
        @endif
        </div>
    </x-site.page-frame>
</div>
