<?php

namespace App\Models\Kuis;

use App\Models\Pertanyaan\PertanyaanModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KuisJawabanModel extends Model
{
    use SoftDeletes;

    protected $table = 't_kuis_jawaban';

    protected $guarded = [];

    /**
     * Cast atribut ke tipe data yang sesuai.
     *
     * @var array
     */
    protected $casts = [
        'is_correct' => 'boolean', // Konversi ke true/false
    ];

    /**
     * Relasi Many-to-One ke model QuizAttempt.
     * Mendapatkan percobaan kuis yang memiliki jawaban ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coba()
    {
        return $this->belongsTo(KuisCobaModel::class, 'quiz_attempt_id'); // Eksplisit foreign key
    }

    /**
     * Relasi Many-to-One ke model Question.
     * Mendapatkan pertanyaan yang dijawab.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanModel::class);
    }
}
