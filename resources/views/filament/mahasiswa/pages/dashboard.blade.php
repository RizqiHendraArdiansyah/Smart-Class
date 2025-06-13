<x-filament::page>
    <h2 class="text-2xl font-bold mb-4 dark:text-white">Materi</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($materiList as $materi)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold dark:text-white line-clamp-2">{{ $materi->title ?? '-' }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                        Kelas: {{ $materi->module->kelas->name ?? '-' }} {{ $materi->module->kelas->offering ?? '' }} - {{ $materi->module->kelas->batch_year ?? '' }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                        Modul: {{ $materi->module->title ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $materi->created_at ? $materi->created_at->format('d M Y') : '-' }}</p>
                    <p class="mt-2 text-sm dark:text-gray-300">Jumlah Soal: {{ $materi->kuis->count() ?? 0 }}</p>
                </div>
                <a href="{{ route('filament.mahasiswa.pages.materi', $materi->id) }}" class="mt-4 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-4 py-2 rounded text-center transition-colors">Baca Materi</a>
            </div>
        @endforeach
    </div>
</x-filament::page>
