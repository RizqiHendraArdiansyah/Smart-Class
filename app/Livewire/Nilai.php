<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kuis\KuisCobaModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas\KelasModel;

class Nilai extends Component
{
    public $leaderboard = [];
    public $kelasList = [];
    public $selectedKelasId;
    public $selectedDifficulty = '';
    public $difficultyOptions = [
        '' => 'Semua',
        'easy' => 'Mudah',
        'medium' => 'Sedang',
        'hard' => 'Sulit',
    ];

    public function mount()
    {
        $user = Auth::user();

        // Get classes based on user role
        if ($user->hasRole('dosen')) {
            $this->kelasList = KelasModel::get();
        } else {
            // For mahasiswa, only get their enrolled class
            $this->kelasList = $user->kelas;
        }

        $this->selectedKelasId = $this->kelasList->first()->id ?? null;
        $this->updateLeaderboard();
    }

    public function updatedSelectedKelasId()
    {
        $this->updateLeaderboard();
    }

    public function updatedSelectedDifficulty()
    {
        $this->updateLeaderboard();
    }

    public function updateLeaderboard()
    {
        $user = Auth::user();
        $kelas = KelasModel::with('users')->find($this->selectedKelasId);

        // For mahasiswa, ensure they can only view their own class
        if ($user->hasRole('mahasiswa') && !$user->kelas->contains('id', $this->selectedKelasId)) {
            $this->selectedKelasId = $user->kelas->first()->id ?? null;
            $kelas = KelasModel::with('users')->find($this->selectedKelasId);
        }

        $userIds = $kelas ? $kelas->users->pluck('id') : collect();

        $query = KuisCobaModel::selectRaw('user_id, COUNT(*) as total_kuis, SUM(score) as total_nilai')
            ->whereIn('user_id', $userIds);

        if ($this->selectedDifficulty) {
            $query->where('difficulty_level', $this->selectedDifficulty);
        }

        $this->leaderboard = $query
            ->groupBy('user_id')
            ->orderByDesc('total_nilai')
            ->with('user')
            ->get();
    }

    public function render()
    {
        return view('livewire.nilai', [
            'leaderboard' => $this->leaderboard,
            'user_id' => Auth::id(),
            'kelasList' => $this->kelasList,
            'selectedKelasId' => $this->selectedKelasId,
            'difficultyOptions' => $this->difficultyOptions,
            'selectedDifficulty' => $this->selectedDifficulty,
        ]);
    }
}
