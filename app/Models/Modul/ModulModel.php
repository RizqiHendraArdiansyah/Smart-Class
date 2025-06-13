<?php

namespace App\Models\Modul;

use App\Models\Kelas\KelasModel;
use App\Models\Kuis\KuisModel;
use App\Models\Materi\MateriModel;
use App\Models\Pertanyaan\PertanyaanModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ModulModel extends Model
{
    use SoftDeletes;

    protected $table = 'm_modul';

    protected $guarded = [];

    protected $appends = ['progress'];

    public function materi()
    {
        // Otomatis mengurutkan berdasarkan kolom 'order'
        return $this->hasMany(MateriModel::class, 'module_id');
    }

    public function kuis()
    {
        return $this->hasMany(KuisModel::class, 'module_id');
    }

    public function soal()
    {
        return $this->hasMany(PertanyaanModel::class, 'module_id');
    }

    public function kelas() // <-- Tambahkan relasi ini
    {
        // Foreign key otomatis dicari 'class_model_id' atau 'class_id'
        // jika nama model ClassModel.php atau Class.php
        return $this->belongsTo(KelasModel::class, 'class_id'); // Eksplisit lebih aman
    }

    public function getProgressAttribute()
    {
        if (!Auth::check() || $this->materi->isEmpty()) {
            return 0;
        }

        $totalMateri = $this->materi->count();
        $completedMateri = $this->materi()
            ->whereHas('userProgress', function($query) {
                $query->where('user_id', Auth::id())
                    ->where('progress', 100);
            })
            ->count();

        return ($completedMateri / $totalMateri) * 100;
    }
}
