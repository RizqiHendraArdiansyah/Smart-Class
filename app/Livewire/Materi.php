<?php

namespace App\Livewire;

use App\Models\Materi\MateriModel;
use App\Models\Modul\ModulModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Materi\MateriProgressModel;

class Materi extends Component
{
    public $modules;
    public $selectedModule = null;
    public $selectedMateri = null;
    public $showModuleContent = false;
    public $currentPage = 1;
    public $totalPages = 1;
    public $currentProgress = 0;

    protected $listeners = ['showModuleContent'];

    public function mount()
    {
        $user = Auth::user();
        $classIds = $user->kelas->pluck('id');

        $this->modules = ModulModel::with(['materi', 'kelas'])
            ->whereIn('class_id', $classIds)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function showModuleContent($moduleId)
    {
        if (is_array($moduleId) && isset($moduleId['moduleId'])) {
            $moduleId = $moduleId['moduleId'];
        }

        $this->selectedModule = ModulModel::with(['materi', 'kelas'])->find($moduleId);

        if ($this->selectedModule && $this->selectedModule->materi->count() > 0) {
            $this->totalPages = $this->selectedModule->materi->count();
            $this->currentPage = 1;
            $this->showMateriContent($this->selectedModule->materi->first()->id);
        }

        $this->showModuleContent = true;
        $this->calculateProgress();
    }

    public function showMateriContent($materiId)
    {
        $this->selectedMateri = MateriModel::with(['module', 'module.kelas'])->find($materiId);

        if (!$this->selectedMateri) {
            session()->flash('error', 'Materi tidak ditemukan.');
            return;
        }

        // Hitung halaman berdasarkan urutan materi
        $this->currentPage = $this->selectedModule->materi->search(function($item) {
            return $item->id === $this->selectedMateri->id;
        }) + 1;

        $this->updateProgress();
    }

    public function nextMateri()
    {
        if (!$this->selectedMateri || !$this->selectedModule) return;

        $currentIndex = $this->selectedModule->materi->search(function($item) {
            return $item->id === $this->selectedMateri->id;
        });

        if ($currentIndex !== false && $currentIndex < $this->selectedModule->materi->count() - 1) {
            $nextMateri = $this->selectedModule->materi[$currentIndex + 1];
            $this->showMateriContent($nextMateri->id);
        }
    }

    public function previousMateri()
    {
        if (!$this->selectedMateri || !$this->selectedModule) return;

        $currentIndex = $this->selectedModule->materi->search(function($item) {
            return $item->id === $this->selectedMateri->id;
        });

        if ($currentIndex !== false && $currentIndex > 0) {
            $previousMateri = $this->selectedModule->materi[$currentIndex - 1];
            $this->showMateriContent($previousMateri->id);
        }
    }

    public function calculateProgress()
    {
        if (!Auth::check() || !$this->selectedModule) return 0;

        $totalMateri = $this->selectedModule->materi->count();
        if ($totalMateri === 0) return 0;

        $completedMateri = MateriProgressModel::where('user_id', Auth::id())
            ->whereIn('materi_id', $this->selectedModule->materi->pluck('id'))
            ->where('progress', 100)
            ->count();

        $this->currentProgress = ($completedMateri / $totalMateri) * 100;
        return $this->currentProgress;
    }

    public function updateProgress()
    {
        if (!Auth::check() || !$this->selectedMateri) return;

        // Tandai materi saat ini sebagai selesai
        MateriProgressModel::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'materi_id' => $this->selectedMateri->id,
            ],
            [
                'progress' => 100,
                'completed_at' => now()
            ]
        );

        $this->calculateProgress();
    }

    public function backToModules()
    {
        $this->selectedModule = null;
        $this->selectedMateri = null;
        $this->showModuleContent = false;
        $this->currentPage = 1;
        $this->totalPages = 1;
        $this->currentProgress = 0;
    }

    public function render()
    {
        return view('livewire.materi');
    }
}
