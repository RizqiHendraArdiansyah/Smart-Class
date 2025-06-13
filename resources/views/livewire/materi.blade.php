<div class="min-h-screen py-2">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Daftar Modul -->
        @if(!$showModuleContent)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($modules as $modul)
                    <div wire:click="$dispatch('showModuleContent', { moduleId: {{ $modul->id }} })"
                         class="dark:bg-gray-800 bg-sky-50 rounded-lg shadow-lg overflow-hidden cursor-pointer transform transition-all duration-300 hover:scale-105">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <span class="text-sm font-medium text-gray-400">
                                    {{ $modul->kelas->name }}
                                </span>
                                @if($modul->created_at->isToday())
                                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-yellow-400 text-black rounded-full">
                                        Baru
                                    </span>
                                @endif
                            </div>

                            <h2 class="text-xl font-semibold dark:text-white text-black mb-2">{{ $modul->title }}</h2>
                            <p class="text-gray-400 text-sm mb-4">{{ $modul->description }}</p>

                            <div class="flex items-center text-gray-400 text-sm mt-4">
                                <div class="flex items-center mr-4">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $modul->waktu ?? '0' }} menit</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>{{ $modul->materi->sum('pages') ?? '1' }} halaman</span>
                                </div>
                            </div>

                            @if(Auth::check())
                                <div class="mt-4">
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $modul->progress }}%"></div>
                                    </div>
                                    <div class="mt-1 text-right text-xs text-gray-400">
                                        {{ number_format($modul->progress) }}% selesai
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        <!-- Konten Materi -->
        @else
            @if (!$selectedMateri)
                <div class="flex flex-col items-center justify-center py-24">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h3m4 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <p class="text-gray-400 text-lg mb-6">Materi masih belum ada</p>
                    <button wire:click="backToModules" class="flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Modul
                    </button>
                </div>
            @else
                <div class="dark:bg-gray-800 bg-sky-50 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <button wire:click="backToModules" class="flex items-center text-gray-400 hover:text-white">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Daftar Modul
                            </button>
                            <div class="flex items-center">
                                <span class="text-sm font-medium dark:text-gray-400 text-black">
                                    {{ $selectedModule->kelas->name ?? '2A' }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h1 class="text-3xl font-bold dark:text-white text-black mb-2">{{ $selectedModule->title }}</h1>
                            <h2 class="text-xl font-semibold dark:text-white text-black mb-4">{{ $selectedMateri->title }}</h2>
                            @if ($selectedMateri->type === 'text')
                                <div class="prose prose-lg prose-invert dark:text-white text-black max-w-none">
                                    {!! $selectedMateri->content !!}
                                </div>
                            @elseif ($selectedMateri->type === 'video')
                                @php
                                    $isYoutube = preg_match('/youtu\\.?be/', $selectedMateri->content);
                                    $youtubeId = null;
                                    if ($isYoutube) {
                                        if (preg_match('/youtu\.be\\/([\w-]+)/', $selectedMateri->content, $matches)) {
                                            $youtubeId = $matches[1];
                                        } elseif (preg_match('/v=([\w-]+)/', $selectedMateri->content, $matches)) {
                                            $youtubeId = $matches[1];
                                        }
                                    }
                                @endphp
                                @if ($isYoutube && $youtubeId)
                                    <div class="aspect-w-20 aspect-h-20 mb-4">
                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                            frameborder="0"
                                            allowfullscreen
                                            class="w-full h-full rounded-lg"
                                        ></iframe>
                                    </div>
                                @else
                                    <video controls class="w-full rounded-lg mb-4">
                                        <source src="{{ $selectedMateri->content }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <a href="{{ $selectedMateri->content }}" target="_blank" class="text-blue-400 underline">Buka Video</a>
                                @endif
                            @elseif ($selectedMateri->type === 'pdf')
                                <iframe
                                    src="{{ asset('storage/' . $selectedMateri->content) }}"
                                    class="w-full h-[600px] rounded-lg border"
                                ></iframe>
                                <a href="{{ asset('storage/' . $selectedMateri->content) }}" target="_blank" class="text-blue-400 underline block mt-2">Download PDF</a>
                            @elseif ($selectedMateri->type === 'link')
                                <a href="{{ $selectedMateri->content }}" target="_blank" class="text-blue-400 underline text-lg">
                                    {{ $selectedMateri->content }}
                                </a>
                            @endif
                        </div>

                        <!-- Navigation and Progress -->
                        <div class="mt-8">
                            <div class="text-gray-400 mb-2">
                                Halaman {{ $currentPage }} dari {{ $totalPages }}
                            </div>

                            @if(Auth::check())
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-700 rounded-full h-2 mb-4">
                                    <div class="bg-yellow-400 h-2 rounded-full"
                                         style="width: {{ ($currentPage / $totalPages) * 100 }}%">
                                    </div>
                                </div>
                            @endif

                            <!-- Combined Navigation -->
                            <div class="flex justify-between items-center">
                                <button wire:click="previousMateri"
                                        class="px-4 py-2 bg-gray-700 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ !$selectedModule || $selectedModule->materi->first()->id === $selectedMateri->id ? 'disabled' : '' }}>
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Sebelumnya
                                    </span>
                                </button>
                                <button wire:click="nextMateri"
                                        class="px-4 py-2 bg-yellow-400 text-black rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ !$selectedModule || $selectedModule->materi->last()->id === $selectedMateri->id ? 'disabled' : '' }}>
                                    <span class="flex items-center">
                                        Selanjutnya
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
