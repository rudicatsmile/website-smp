<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Program Unggulan</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Program-program unggulan sekolah untuk mengembangkan potensi dan bakat siswa</p>
    </div>

    @if($programs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($programs as $program)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    @if($program->image)
                        <div class="h-48 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                            <img src="{{ asset('storage/' . $program->image) }}"
                                 alt="{{ $program->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                            <x-heroicon-o-sparkles class="w-20 h-20 text-white opacity-80" />
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $program->title }}</h3>
                        @if($program->excerpt)
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $program->excerpt }}</p>
                        @endif

                        @if($program->button_link)
                            <a href="{{ $program->button_link }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                {{ $program->button_text }}
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-2" />
                            </a>
                        @else
                            <a href="{{ route('program.show', $program->slug) }}"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                {{ $program->button_text }}
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-2" />
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            {{ $programs->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <x-heroicon-o-sparkles class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500">Belum ada program unggulan yang tersedia.</p>
        </div>
    @endif
</div>
