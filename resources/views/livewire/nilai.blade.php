<div class="w-full mx-auto py-8">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-2 text-gray-900 dark:text-gray-100">
                <span>🏆</span> Leaderboard Nilai Mahasiswa
            </h2>
        </div>
        <div class="mb-4 flex gap-4 items-center">
            @if(auth()->user()->hasRole('dosen'))
                <div>
                    <label class="font-semibold text-gray-700 dark:text-gray-200 mr-2">Kelas:</label>
                    <select wire:model="selectedKelasId" wire:change="updateLeaderboard" class="input input-bordered dark:bg-gray-900 dark:text-gray-100">
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div>
                <label class="font-semibold text-gray-700 dark:text-gray-200 mr-2">Tingkat Kesulitan:</label>
                <select wire:model="selectedDifficulty" wire:change="updateLeaderboard" class="input input-bordered dark:bg-gray-900 dark:text-gray-100">
                    @foreach($difficultyOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex items-end justify-center gap-8 mb-8">
            @if($leaderboard->count() > 0)
                <!-- Podium 2 -->
                <div class="flex flex-col items-center">
                    @if(isset($leaderboard[1]))
                        <div class="w-20 h-20 rounded-full border-4 border-gray-300 dark:border-gray-700 overflow-hidden mb-2">
                            <img src="{{ asset('storage/' . $leaderboard[1]->user->avatar_url) ?? 'https://ui-avatars.com/api/?name='.urlencode($leaderboard[1]->user->name) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $leaderboard[1]->user->name }}</div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm mb-1">{{ $leaderboard[1]->total_nilai }} poin</div>
                        <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-b-lg flex items-center justify-center font-bold text-lg">2</div>
                    @endif
                </div>
                <!-- Podium 1 -->
                <div class="flex flex-col items-center">
                    @if(isset($leaderboard[0]))
                        <div class="w-24 h-24 rounded-full border-4 border-yellow-400 overflow-hidden mb-2 ring-4 ring-yellow-200 dark:ring-yellow-700">
                            <img src="{{ asset('storage/' . $leaderboard[0]->user->avatar_url) ?? 'https://ui-avatars.com/api/?name='.urlencode($leaderboard[0]->user->name) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $leaderboard[0]->user->name }}</div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm mb-1">{{ $leaderboard[0]->total_nilai }} poin</div>
                        <div class="w-8 h-12 bg-yellow-400 dark:bg-yellow-700 rounded-b-lg flex items-center justify-center font-bold text-lg text-white">1</div>
                    @endif
                </div>
                <!-- Podium 3 -->
                <div class="flex flex-col items-center">
                    @if(isset($leaderboard[2]))
                        <div class="w-20 h-20 rounded-full border-4 border-amber-700 dark:border-amber-900 overflow-hidden mb-2">
                            <img src="{{ asset('storage/' . $leaderboard[2]->user->avatar_url) ?? 'https://ui-avatars.com/api/?name='.urlencode($leaderboard[2]->user->name) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $leaderboard[2]->user->name }}</div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm mb-1">{{ $leaderboard[2]->total_nilai }} poin</div>
                        <div class="w-8 h-6 bg-amber-700 dark:bg-amber-900 rounded-b-lg flex items-center justify-center font-bold text-lg text-white">3</div>
                    @endif
                </div>
            @else
                <div class="w-full text-center text-gray-400 dark:text-gray-500 py-8">
                    Belum ada data leaderboard untuk kelas ini.
                </div>
            @endif
        </div>
        {{-- <div class="mb-4 flex justify-end">
            <input type="text" class="input input-bordered w-64" placeholder="Cari siswa...">
        </div> --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-200">
                        <th class="py-2 px-3">Nomor</th>
                        <th class="py-2 px-3 text-left">Nama Mahasiswa</th>
                        <th class="py-2 px-3">Kuis</th>
                        <th class="py-2 px-3">Nilai Kuis</th>
                    </tr>
                </thead>
                <tbody>
                    @if($leaderboard->count())
                        @foreach($leaderboard as $i => $row)
                            <tr class="{{ $row->user_id == $user_id ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : 'dark:text-gray-100' }}">
                                <td class="py-2 px-3 text-center">{{ $i+1 }}</td>
                                <td class="py-2 px-3 flex items-center gap-2">
                                    <img src="{{ asset('storage/' . $row->user->avatar_url) ?? 'https://ui-avatars.com/api/?name='.urlencode($row->user->name) }}" class="w-8 h-8 rounded-full" alt="">
                                    {{ $row->user->name }}
                                    @if($row->user_id == $user_id)
                                        <span class="ml-2 px-2 py-0.5 rounded bg-indigo-200 dark:bg-indigo-700 text-indigo-700 dark:text-indigo-200 text-xs">Anda</span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-center">{{ $row->total_kuis }}</td>
                                <td class="py-2 px-3 text-center">{{ $row->total_nilai }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center text-gray-400 dark:text-gray-500 py-8">
                                Belum ada data leaderboard untuk kelas ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
