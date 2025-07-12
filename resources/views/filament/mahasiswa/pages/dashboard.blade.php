<x-filament::page>
      <div class="mb-8 text-center">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Materi Yang Tersedia</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($materiList as $materi)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-2xl transform hover:scale-[1.02] transition-all duration-300 p-5 relative flex flex-col">

            <div class="flex-1">
                <h3 class="text-lg font-bold dark:text-white mb-1 line-clamp-2">
                    {{ $materi->title ?? '-' }}
                </h3>

                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 14l6.16-3.422A12.042 12.042 0 0112 21.5a12.042 12.042 0 01-6.16-10.922L12 14z"/>
                    </svg>
                    Kelas: {{ $materi->module->kelas->name ?? '-' }} {{ $materi->module->kelas->offering ?? '' }} - {{ $materi->module->kelas->batch_year ?? '' }}
                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 8h8M8 12h8m-8 4h4"/>
                    </svg>
                    Modul: {{ $materi->module->title ?? '-' }}
                </div>

                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                    {{ $materi->created_at ? $materi->created_at->format('d M Y') : '-' }}
                </p>
            </div>

            <a href="{{ route('filament.mahasiswa.pages.materi', $materi->id) }}"
               class="mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg flex justify-center items-center gap-2 transition duration-300">
                Lihat Materi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    @endforeach
</div>
</x-filament::page>
