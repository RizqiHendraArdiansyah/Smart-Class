<div class="w-full mx-auto py-6 px-2 md:px-0">
    @if($step === 'list')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Daftar Kuis</h2>
            {{-- <div class="flex gap-2 bg-white/80 dark:bg-gray-800/80 rounded-full p-1">
                <button wire:click="setFilter('all')" class="px-4 py-1 rounded-full text-sm font-semibold {{ $filter === 'all' ? 'bg-white shadow text-indigo-600' : 'text-gray-500' }}">Semua</button>
                <button wire:click="setFilter('done')" class="px-4 py-1 rounded-full text-sm font-semibold {{ $filter === 'done' ? 'bg-white shadow text-indigo-600' : 'text-gray-500' }}">Selesai</button>
                <button wire:click="setFilter('notyet')" class="px-4 py-1 rounded-full text-sm font-semibold {{ $filter === 'notyet' ? 'bg-white shadow text-indigo-600' : 'text-gray-500' }}">Belum</button>
                <button wire:click="setFilter('upcoming')" class="px-4 py-1 rounded-full text-sm font-semibold {{ $filter === 'upcoming' ? 'bg-white shadow text-indigo-600' : 'text-gray-500' }}">Akan Datang</button>
            </div> --}}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($this->filteredQuizzes as $quiz)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow relative overflow-hidden border-t-8 {{ $quiz->sudah_dikerjakan ? 'border-gradient-to-r from-orange-400 to-pink-500' : ($quiz->title == 'JavaScript Dasar' ? 'border-yellow-400' : ($quiz->title == 'React Hooks' ? 'border-cyan-400' : 'border-blue-400')) }}">
                    <div class="flex items-center gap-4 p-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-2xl
                            {{ $quiz->title == 'HTML Dasar' ? 'bg-red-100 text-red-500 dark:bg-red-900 dark:text-red-300'
                            : ($quiz->title == 'CSS Fundamental' ? 'bg-blue-100 text-blue-500 dark:bg-blue-900 dark:text-blue-300'
                            : ($quiz->title == 'JavaScript Dasar' ? 'bg-yellow-100 text-yellow-500 dark:bg-yellow-900 dark:text-yellow-300'
                            : 'bg-cyan-100 text-cyan-500 dark:bg-cyan-900 dark:text-cyan-300')) }}">
                            @if($quiz->title == 'HTML Dasar')
                                <span>📄</span>
                            @elseif($quiz->title == 'CSS Fundamental')
                                <span>✨</span>
                            @elseif($quiz->title == 'JavaScript Dasar')
                                <span>⚡</span>
                            @else
                                <span>🧠</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $quiz->title }}</h3>
                                @if($quiz->sudah_dikerjakan)
                                    <span class="ml-2 px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 text-xs font-semibold">Selesai</span>
                                @endif
                            </div>
                            {{-- <div class="text-xs text-gray-400">Kode: {{ $quiz->id }}</div> --}}
                            <div class="flex gap-2 mt-2 flex-wrap">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs font-medium">{{ $quiz->title }}</span>
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs font-medium">{{ $quiz->description ?? 'Mudah' }}</span>
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs font-medium">{{ $quiz->time_limit_minutes ?? '-' }} menit</span>
                            </div>
                            <div class="flex flex-col gap-1 mt-2">
                                @if($quiz->attempt)
                                    <div class="text-green-600 dark:text-green-300 font-bold text-lg">
                                        Nilai Kuis : {{ $quiz->attempt->score }} ({{ ucfirst($quiz->attempt->difficulty_level) }})
                                    </div>
                                    <span class="ml-2 px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 text-xs font-semibold">Kuis telah selesai dikerjakan</span>
                                @else
                                    {{-- <button wire:click="selectQuiz({{ $quiz->id }})" class="mt-1 px-4 py-2 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold shadow hover:from-purple-600 hover:to-indigo-600 transition">Kerjakan</button> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 px-4 pb-4">
                        @if($quiz->is_aktif == 'datang')
                            <button class="bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-6 py-2 rounded-full font-semibold cursor-not-allowed" disabled>Akan Datang</button>
                        @elseif($quiz->sudah_dikerjakan)
                            {{-- <button class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-2 rounded-full font-semibold shadow hover:from-purple-600 hover:to-indigo-600 transition">Ulangi</button> --}}
                        @else
                            @if($quiz->is_passcode_enabled)
                                <button wire:click="selectQuiz({{ $quiz->id }})" class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-2 rounded-full font-semibold shadow hover:from-purple-600 hover:to-indigo-600 transition">Kerjakan Sekarang<span class="ml-2">→</span></button>
                            @else
                                <button wire:click="selectQuiz({{ $quiz->id }})" class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-2 rounded-full font-semibold shadow hover:from-purple-600 hover:to-indigo-600 transition">Kerjakan Sekarang <span class="ml-2">→</span></button>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center text-gray-400 dark:text-gray-500 py-12">Belum ada kuis untuk kelas/materi Anda.</div>
            @endforelse
        </div>
    @elseif($step === 'difficulty')
        @php
            $userAttempts = \App\Models\Kuis\KuisCobaModel::where('quiz_id', $selectedQuiz->id)
                ->where('user_id', Auth::id())
                ->pluck('difficulty_level')
                ->toArray();
        @endphp
        <div class="flex flex-col items-center justify-center min-h-[60vh]">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg flex flex-col items-center">
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100 text-center">Pilih Tingkat Kesulitan</h3>
                <div class="flex gap-10 mb-4">
                    @foreach(['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'] as $level => $label)
                        @if(in_array($level, $userAttempts))
                        <button class="w-32 h-32 rounded-full bg-gray-400 text-white font-semibold shadow cursor-not-allowed flex items-center justify-center text-md text-center" disabled>
                            <span>{{ $label }}<br><span class="text-xs">(Sudah Dikerjakan)</span></span>
                        </button>
                        @else
                        <button wire:click="chooseDifficulty('{{ $level }}')" class="w-32 h-32 rounded-full flex items-center justify-center text-md font-semibold shadow transition {{ $level == 'easy' ? 'bg-green-500 hover:bg-green-600' : ($level == 'medium' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-red-500 hover:bg-red-600') }} text-white text-center">
                            {{ $label }}
                        </button>
                        @endif
                    @endforeach
                </div>
                <a href="#" wire:click="backToList" class="text-gray-500 dark:text-gray-300 text-xs mt-2">Kembali ke Daftar Kuis</a>
            </div>
        </div>
    @elseif($step === 'passcode')
        <div class="flex justify-center items-center min-h-[60vh]">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 w-full max-w-md flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-violet-100 dark:bg-violet-900 flex items-center justify-center text-3xl mb-4">
                    <span>🔒</span>
                </div>
                <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-gray-100">Masukkan Passcode</h3>
                <p class="text-gray-500 dark:text-gray-400 text-center mb-4">Masukkan passcode untuk mengakses kuis ini</p>
                <div class="w-full">
                    <input type="text" wire:model="passcodeInput" class="input input-bordered w-full mb-4 text-center text-lg py-3 dark:bg-gray-900 dark:text-gray-100" placeholder="Masukkan passcode">
                    @if($error)
                        <div class="text-red-500 dark:text-red-300 mb-2 text-center">{{ $error }}</div>
                    @endif
                </div>
                <div class="flex gap-4 w-full">
                    <button wire:click="checkPasscode" class="flex-1 bg-gradient-to-r from-purple-500 to-indigo-500 text-white py-3 rounded-full font-semibold text-lg shadow hover:from-purple-600 hover:to-indigo-600 transition">Mulai Kuis</button>
                </div>
                <a href="#" wire:click="backToDifficulty" class="text-gray-500 dark:text-gray-300 text-sm mt-4">Kembali ke Pilihan Kesulitan</a>
            </div>
        </div>
    @elseif($step === 'quiz')
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            @if($selectedQuiz->is_passcode_enabled && !$passcodeVerified)
                <div class="flex flex-col items-center justify-center py-8">
                    <div class="w-16 h-16 rounded-full bg-violet-100 dark:bg-violet-900 flex items-center justify-center text-3xl mb-4">
                        <span>🔒</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-gray-100">Masukkan Passcode</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center mb-4">Masukkan passcode untuk mengakses kuis ini</p>
                    <div class="w-full max-w-md">
                        <input type="text" wire:model="passcodeInput" class="input input-bordered w-full mb-4 text-center text-lg py-3 dark:bg-gray-900 dark:text-gray-100" placeholder="Masukkan passcode">
                        @if($error)
                            <div class="text-red-500 dark:text-red-300 mb-2 text-center">{{ $error }}</div>
                        @endif
                        <div class="flex gap-4 w-full">
                            <button wire:click="checkPasscode" class="flex-1 bg-gradient-to-r from-purple-500 to-indigo-500 text-white py-3 rounded-full font-semibold text-lg shadow hover:from-purple-600 hover:to-indigo-600 transition">Mulai Kuis</button>
                        </div>
                        <a href="#" wire:click="backToDifficulty" class="text-gray-500 dark:text-gray-300 text-sm mt-4 block text-center">Kembali ke Pilihan Kesulitan</a>
                    </div>
                </div>
            @else
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-2">
                    <div>
                        <div class="font-bold text-xl md:text-2xl text-gray-900 dark:text-gray-100">{{ $selectedQuiz->title }}</div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <div class="flex items-center gap-2 text-red-500 dark:text-red-300 font-semibold text-sm md:text-base"><span>⏰</span> Sisa waktu: <span wire:poll.1000ms="tickTimer">{{ gmdate('i:s', $timer) }}</span></div>
                        <div class="text-xs text-gray-400 dark:text-gray-300">{{ $currentQuestionIndex+1 }}/{{ count($questions) }}</div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-xs text-gray-400 dark:text-gray-300">Tingkat Kesulitan</div>
                        <div class="flex gap-2">
                            @if($currentDifficulty == 'easy')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300">Mudah</span>
                            @elseif($currentDifficulty == 'medium')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300">Sedang</span>
                            @elseif($currentDifficulty == 'hard')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300">Sulit</span>
                            @endif
                            {{-- <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $currentDifficulty === 'easy' ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">Mudah</span>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $currentDifficulty === 'medium' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">Sedang</span>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $currentDifficulty === 'hard' ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">Sulit</span> --}}
                        </div>
                    </div>
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full mt-1 mb-2">
                        <div class="h-2 bg-purple-500 rounded-full transition-all duration-300" style="width: {{ count($questions) > 0 ? round((($currentQuestionIndex+1)/count($questions))*100) : 0 }}%"></div>
                    </div>
                    {{-- <div class="flex justify-between text-xs text-gray-400 dark:text-gray-300">
                        <div>Mudah: {{ count($difficultyGroups['easy'] ?? []) }} soal</div>
                        <div>Sedang: {{ count($difficultyGroups['medium'] ?? []) }} soal</div>
                        <div>Sulit: {{ count($difficultyGroups['hard'] ?? []) }} soal</div>
                    </div> --}}
                </div>
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-lg px-4 py-2 mb-4 font-semibold flex items-center gap-2">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif
                @if(session('xp_bonus'))
                    <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-lg px-4 py-2 mb-4 font-semibold flex items-center gap-2"><span>✨</span> Streak {{ session('streak') }}! +{{ session('xp_bonus') }} XP bonus</div>
                @endif
                @if(count($questions) > 0)
                    <div class="bg-indigo-50 dark:bg-indigo-900 rounded-xl p-6 mb-4">
                        @php $q = $questions[$currentQuestionIndex]; @endphp
                        <div class="flex items-center justify-between mb-4">
                            <div class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $q->text }}</div>
                            {{-- <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $q->difficulty_level === 'easy' ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300'
                                : ($q->difficulty_level === 'medium' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300'
                                : 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300') }}">
                                {{ $q->difficulty_level === 'easy' ? 'Mudah' : ($q->difficulty_level === 'medium' ? 'Sedang' : 'Sulit') }}
                            </span> --}}
                        </div>
                        @if($q->type == 'multiple_choice')
                            @php $opsi = is_string($q->options) ? json_decode($q->options, true) : $q->options; @endphp
                            <div class="flex flex-col gap-3">
                                @foreach($opsi as $opt)
                                    {{-- <label class="flex items-center gap-3 cursor-pointer bg-white dark:bg-gray-800 rounded-lg px-4 py-3 border border-gray-200 dark:border-gray-700 hover:border-purple-400 transition">
                                        <input type="radio" wire:click="pilihJawaban({{ $q->id }}, '{{ $opt['option'] }}')" value="{{ $opt['option'] }}" class="form-radio text-purple-500">
                                        <span class="text-base text-gray-900 dark:text-gray-100">{{ $opt['option'] }}</span>
                                    </label> --}}
                            <div class="flex items-center gap-3 cursor-pointer bg-white dark:bg-gray-800 rounded-lg px-4 py-3 border border-gray-200 dark:border-gray-700 hover:border-purple-400 transition">
                    <input type="radio"
                    name="answer_{{ $q->id }}"
                    value="{{ $opt['option'] }}"
                    wire:model="answers.{{ $q->id }}"
                    wire:click="pilihJawaban({{ $q->id }}, '{{ $opt['option'] }}')">
                        <span class="text-base text-gray-900 dark:text-gray-100">{{ $opt['option'] }}</span>
                            </div>
                                @endforeach
                            </div>
                        @elseif($q->type == 'true_false')
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer bg-white dark:bg-gray-800 rounded-lg px-4 py-3 border border-gray-200 dark:border-gray-700 hover:border-purple-400 text-center font-bold text-lg transition">
                                    <input type="radio" wire:model="answers.{{ $q->id }}" value="true" class="form-radio text-purple-500 mr-2" wire:click="pilihJawaban({{ $q->id }}, 'true')"> Benar
                                </label>
                                <label class="flex-1 cursor-pointer bg-white dark:bg-gray-800 rounded-lg px-4 py-3 border border-gray-200 dark:border-gray-700 hover:border-purple-400 text-center font-bold text-lg transition">
                                    <input type="radio" wire:model="answers.{{ $q->id }}" value="false" class="form-radio text-purple-500 mr-2" wire:click="pilihJawaban({{ $q->id }}, 'false')"> Salah
                                </label>
                            </div>
                        @elseif($q->type == 'short_answer')
                            <input type="text" wire:model.lazy="answers.{{ $q->id }}" wire:change="pilihJawaban({{ $q->id }}, $event.target.value)" class="input input-bordered w-full text-lg px-4 py-3 mt-2 dark:bg-gray-900 dark:text-gray-100" placeholder="Ketik jawaban Anda di sini...">
                        @elseif($q->type == 'essay')
                            <textarea wire:model.lazy="answers.{{ $q->id }}" wire:change="pilihJawaban({{ $q->id }}, $event.target.value)" class="textarea textarea-bordered w-full text-lg px-4 py-3 mt-2 dark:bg-gray-900 dark:text-gray-100" rows="4" placeholder="Ketik jawaban Anda di sini..."></textarea>
                        @endif
                    </div>
                @endif
                <div class="flex justify-between mt-4">
                    <button type="button" wire:click="prevQuestion" @if($currentQuestionIndex == 0) disabled @endif class="px-6 py-2 rounded-full border border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300 font-semibold bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition flex items-center gap-2"><span class="text-lg">←</span> Sebelumnya</button>
                    @if($currentQuestionIndex < count($questions) - 1)
                        <button type="button" wire:click="nextQuestion" class="px-6 py-2 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold shadow hover:from-purple-600 hover:to-indigo-600 transition flex items-center gap-2">Selanjutnya <span class="text-lg">→</span></button>
                    @else
                        <button type="button" wire:click="submitQuiz" class="px-6 py-2 rounded-full bg-green-500 text-white font-semibold shadow hover:bg-green-600 transition flex items-center gap-2">Selesai <span class="text-lg">✔️</span></button>
                    @endif
                </div>
            @endif
        </div>
    @elseif($step === 'result')
        <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col items-center">
            <div class="flex flex-col items-center mb-6">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-4xl text-white font-bold">{{ explode(' ', $score)[0] }}</div>
                    <div class="absolute top-0 right-0 bg-yellow-400 dark:bg-yellow-700 text-white text-xs font-bold rounded-full px-2 py-1">+{{ explode(' ', $score)[0] }}</div>
                </div>
                <h3 class="text-2xl font-bold mt-4 mb-2 text-gray-900 dark:text-gray-100">Yeyyy, Kuis Selesai!</h3>
                <div class="text-lg font-semibold mb-1 text-gray-900 dark:text-gray-100">Nilai yang didapat: {{ $score }}</div>
                <div class="text-gray-500 dark:text-gray-300 mb-2">Tetap Semangat dan Terus Belajar Yaaa!!!.</div>
            </div>
            <div class="w-full mb-6">
                @foreach($questions as $q)
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 mb-3 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $q->text }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-300">Jawaban Anda: <span class="text-black dark:text-gray-100">{{ $userAnswers[$q->id] ?? '-' }}</span></div>
                        </div>
                        @if(isset($q->is_correct))
                            <span class="mt-2 md:mt-0 px-3 py-1 rounded-full text-xs font-bold {{ $q->is_correct ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300' }} flex items-center gap-1">
                                <span class="text-lg">{{ $q->is_correct ? '✔️' : '❌' }}</span> {{ $q->is_correct ? 'Benar' : 'Salah' }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="flex gap-4 w-full">
                <button wire:click="backToList" class="flex-1 px-6 py-3 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold shadow hover:from-purple-600 hover:to-indigo-600 transition">Kembali ke Daftar Kuis</button>
                {{-- <button class="flex-1 px-6 py-3 rounded-full border border-gray-300 text-gray-600 font-semibold bg-white hover:bg-gray-100 transition">Ulangi Kuis</button> --}}
            </div>
        </div>
    @elseif($step === 'empty')
        <div class="flex flex-col items-center justify-center min-h-[60vh]">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 w-full max-w-md flex flex-col items-center">
                <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Soal belum ada</h3>
                <p class="text-gray-500 dark:text-gray-300 mb-4">Belum ada soal untuk tingkat kesulitan yang dipilih.</p>
                <a href="#" wire:click="backToList" class="text-gray-500 dark:text-gray-300 text-sm mt-2">Kembali ke Daftar Kuis</a>
            </div>
        </div>
    @endif
</div>
