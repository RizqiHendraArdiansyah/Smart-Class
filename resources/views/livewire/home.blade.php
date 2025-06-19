<div x-data="{ open: false }" class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-100 flex flex-col">
    <!-- Navbar -->
    <nav class="w-full bg-white/80 shadow-md fixed top-0 left-0 z-30 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-3 px-2 md:px-4">
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-2">
    <img src="{{ asset('storage/gambar/logo.png') }}"
        alt="Logo SMART CLASS" class="h-9 w-9 rounded-full bg-white object-contain border border-gray-200" />
    <span class="font-bold text-indigo-800 text-lg">SMART CLASS</span>
</div>
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-2 md:space-x-3">
                <a class="transition hover:scale-105 bg-indigo-600 hover:bg-indigo-700 text-white px-3 md:px-4 py-2 rounded-lg font-semibold shadow text-sm md:text-base"
                    wire:navigate href="{{ route('filament.dosen.auth.login') }}">Login Dosen</a>
                <a class="transition hover:scale-105 bg-blue-500 hover:bg-blue-600 text-white px-3 md:px-4 py-2 rounded-lg font-semibold shadow text-sm md:text-base"
                    wire:navigate href="{{ route('filament.mahasiswa.auth.login') }}">Login Mahasiswa</a>
                <a class="transition hover:scale-105 bg-green-500 hover:bg-green-600 text-white px-3 md:px-4 py-2 rounded-lg font-semibold shadow text-sm md:text-base"
                    wire:navigate href="{{ route('filament.mahasiswa.auth.register') }}">Register Akun</a>
            </div>
            <!-- Hamburger -->
            <button @click="open = !open"
                class="md:hidden flex items-center justify-center p-2 rounded hover:bg-gray-200 transition">
                <svg class="w-7 h-7 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <!-- Mobile Sidebar -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 md:hidden" x-cloak>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" @click="open = false"></div>
            <!-- Sidebar -->
            <div class="absolute left-0 top-0 h-full w-72 bg-white shadow-2xl flex flex-col pt-8 pb-8 px-7"
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full" style="min-height: 100vh;">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/Logo_Universitas_Brawijaya.png"
                            alt="Logo Kampus" class="h-9 w-9 rounded-full" />
                        <span class="font-bold text-indigo-800 text-lg">SMART CLASS</span>
                    </div>
                    <button @click="open = false" class="p-2 ml-2 rounded hover:bg-gray-200 transition">
                        <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-col gap-4 mt-4">
                    <a wire:navigate href="{{ route('filament.dosen.auth.login') }}"
                        class="transition w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg font-semibold shadow text-base text-left block">Login
                        Dosen</a>
                    <a wire:navigate href="{{ route('filament.mahasiswa.auth.login') }}"
                        class="transition w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold shadow text-base text-left block">Login
                        Mahasiswa</a>
                    <a wire:navigate href="{{ route('filament.mahasiswa.auth.register') }}"
                        class="transition w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-semibold shadow text-base text-left block">Register
                        Akun</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section
    class="flex-1 flex flex-col-reverse lg:flex-row items-center justify-center gap-8 md:gap-16 pt-28 md:pt-36 pb-10 w-full max-w-6xl mx-auto px-2 md:px-6">
    <div class="w-full lg:pr-16 flex flex-col justify-center text-center lg:text-left animate-fade-in-up">
        <h1 class="text-3xl md:text-5xl font-extrabold text-indigo-800 mb-3 md:mb-4 leading-tight drop-shadow">SMART CLASS</h1>
        <p class="text-base md:text-lg text-gray-700 mb-6 max-w-xl mx-auto lg:mx-0 text-justify">
            Media Pembelajaran Interaktif 
            dengan mendorong partisipasi aktif mahasiswa dalam kemudahan pembelajaran dasar pemrograman, serta dilengkapi modul dan soal kuis yang inovatif sehingga lebih asik, menarik dan mudah dipahami.
        </p>
    </div>
    <div class="w-full lg:justify-end flex justify-center items-center animate-fade-in mb-8 lg:mb-0">
        <div class="relative w-full max-w-xs sm:max-w-sm md:max-w-md flex justify-center items-center">
            <img src="{{ asset('storage/gambar/logo.png') }}"
                alt="Logo SMART CLASS" 
                style="width: 317px; height: 321px;" 
                class="rounded-full shadow-2xl object-contain border-4 border-white bg-white animate-float" />
        </div>
    </div>
</section>

<!-- Features Section -->
<section
    class="w-full max-w-6xl mx-auto py-8 md:py-14 px-2 md:px-6 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
    <div
        class="bg-white rounded-2xl shadow-xl p-6 md:p-10 flex flex-col items-center text-center hover:scale-[1.03] transition-all duration-300 border border-indigo-50">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" alt="Modul"
            class="w-16 h-16 md:w-20 md:h-20 mb-3 md:mb-4 animate-float" />
        <h3 class="text-lg md:text-xl font-bold text-indigo-700 mb-1 md:mb-2">Modul Materi Efektif</h3>
        <p class="text-gray-600 text-sm md:text-base">Materi pembelajaran yang dikemas secara efisien dan mudah dipahami mahasiswa,
            mendukung penguasaan HTML, CSS, dan JavaScript.</p>
    </div>
    <div
        class="bg-white rounded-2xl shadow-xl p-6 md:p-10 flex flex-col items-center text-center hover:scale-[1.03] transition-all duration-300 border border-indigo-50">
        <img src="{{ asset('storage/gambar/quiz.png') }}" alt="Kuis"
            class="w-16 h-16 md:w-20 md:h-20 mb-3 md:mb-4 animate-float-delay" />
        <h3 class="text-lg md:text-xl font-bold text-indigo-700 mb-1 md:mb-2">Kuis Berbasis Gamifikasi</h3>
        <p class="text-gray-600 text-sm md:text-base">Kuis Interaktif berbasis gamifikasi untuk mengukur softskill
            dan hardskill mahasiswa secara real-time.</p>
    </div>
</section>

    <!-- Animations -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s cubic-bezier(.4, 0, .2, 1) both;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fade-in 1.2s cubic-bezier(.4, 0, .2, 1) both;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-float-delay {
            animation: float 3s ease-in-out 1.5s infinite;
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2.5s infinite;
        }
    </style>
    <!-- Alpine.js for sidebar -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</div>