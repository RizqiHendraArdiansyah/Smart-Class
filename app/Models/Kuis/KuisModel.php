<?php

namespace App\Models\Kuis;

use App\Models\Modul\ModulModel;
use App\Models\Pertanyaan\PertanyaanModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KuisModel extends Model
{
    use SoftDeletes;

    protected $table = 't_kuis';

    protected $guarded = [];

    // protected $fillable = [
    //     'title',
    //     'description',
    //     'materi_id',
    //     'passcode',
    //     'is_passcode_enabled'
    // ];

    /**
     * Relasi Many-to-One ke model Module.
     * Mendapatkan modul pemilik kuis ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modul()
    {
        return $this->belongsTo(ModulModel::class, 'module_id');
    }

    /**
     * Relasi One-to-Many ke model Question.
     * Mendapatkan semua pertanyaan dalam kuis ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pertanyaan()
    {
        return $this->hasMany(PertanyaanModel::class, 'quiz_id');
    }

    /**
     * Relasi One-to-Many ke model QuizAttempt.
     * Mendapatkan semua percobaan yang pernah dilakukan pada kuis ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coba()
    {
        return $this->hasMany(KuisCobaModel::class, 'quiz_id');
    }
    public function soal()
    {
        return $this->hasMany(PertanyaanModel::class, 'quiz_id');
    }
}
