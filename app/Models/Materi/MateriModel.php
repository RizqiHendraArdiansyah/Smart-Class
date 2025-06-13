<?php

namespace App\Models\Materi;

use App\Models\Modul\ModulModel;
use App\Models\Kuis\KuisModel;
use App\Models\Kelas\KelasModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MateriModel extends Model
{
    use SoftDeletes;

    protected $table = 'm_materi';

    protected $guarded = [];

    protected $appends = ['pages', 'progress'];

    public function module()
    {
        // Laravel akan otomatis mencari foreign key 'module_id'
        return $this->belongsTo(ModulModel::class, 'module_id');
    }

    public function kuis()
    {
        // Laravel akan otomatis mencari foreign key 'module_id'
        return $this->hasMany(KuisModel::class, 'module_id');
    }

    public function kelas()
    {
        return $this->hasMany(KelasModel::class, 'module_id');
    }

    public function userProgress()
    {
        return $this->hasMany(MateriProgressModel::class, 'materi_id');
    }

    public function getProgressAttribute()
    {
        // if (auth()->check()) {
            $progress = $this->userProgress()
                ->where('user_id', auth()->id())
                ->first();
            return $progress ? $progress->progress : 0;
        // }
        // return 0;
    }

    public function nextMateri()
    {
        return $this->module->materi()
            ->where('id', '>', $this->id)
            ->orderBy('id')
            ->first();
    }

    public function previousMateri()
    {
        return $this->module->materi()
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Calculate the number of pages based on content length.
     * Assuming average of 250 words per page.
     */
    public function getPagesAttribute()
    {
        // Bersihkan HTML tags
        $cleanContent = strip_tags($this->content);

        // Hitung jumlah kata
        $wordCount = str_word_count($cleanContent);

        // Hitung jumlah halaman (250 kata per halaman)
        $pages = ceil($wordCount / 250);

        // Minimal 1 halaman
        return max(1, $pages);
    }
}
