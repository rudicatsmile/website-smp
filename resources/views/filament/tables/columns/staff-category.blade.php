<div class="px-3 py-4">
    <div class="text-sm text-gray-950 dark:text-white">
        {{ $getState() }}
    </div>
    
    @if($getRecord()->category?->name === 'Guru Pelajaran' && $getRecord()->teachingSubjects->count() > 0)
        <div class="flex flex-wrap gap-1 mt-1.5">
            @foreach($getRecord()->teachingSubjects as $subject)
                <x-filament::badge color="info" size="sm">
                    {{ $subject->name }}
                </x-filament::badge>
            @endforeach
        </div>
    @endif
</div>
