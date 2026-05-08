<div class="container mx-auto px-4 py-12">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-700">Beranda</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('program.index') }}" class="text-emerald-600 hover:text-emerald-700">Program Unggulan</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-600">{{ $program->title }}</li>
        </ol>
    </nav>

    <!-- Main Content -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @if($program->image)
            <div class="h-80 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                <img src="{{ asset('storage/' . $program->image) }}"
                     alt="{{ $program->title }}"
                     class="w-full h-full object-cover">
            </div>
        @else
            <div class="h-80 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                <x-heroicon-o-sparkles class="w-32 h-32 text-white opacity-80" />
            </div>
        @endif

        <div class="p-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $program->title }}</h1>

            @if($program->excerpt)
                <p class="text-lg text-gray-600 mb-6">{{ $program->excerpt }}</p>
            @endif

            @if($program->button_link)
                <a href="{{ $program->button_link }}"
                   target="_blank"
                   class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors mb-8">
                    {{ $program->button_text }}
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2" />
                </a>
            @endif

            @if($program->description)
                <div class="prose prose-lg max-w-none">
                    {!! $program->description !!}
                </div>
            @endif
        </div>
    </div>

    <!-- Related Programs -->
    @if($relatedPrograms->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Program Lainnya</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedPrograms as $relatedProgram)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        @if($relatedProgram->image)
                            <div class="h-32 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $relatedProgram->image) }}"
                                     alt="{{ $relatedProgram->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-32 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                <x-heroicon-o-sparkles class="w-12 h-12 text-white opacity-80" />
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $relatedProgram->title }}</h3>
                            @if($relatedProgram->button_link)
                                <a href="{{ $relatedProgram->button_link }}"
                                   target="_blank"
                                   class="inline-flex items-center text-emerald-600 hover:text-emerald-700 text-sm">
                                    {{ $relatedProgram->button_text }}
                                    <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                                </a>
                            @else
                                <a href="{{ route('program.show', $relatedProgram->slug) }}"
                                   class="inline-flex items-center text-emerald-600 hover:text-emerald-700 text-sm">
                                    {{ $relatedProgram->button_text }}
                                    <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
