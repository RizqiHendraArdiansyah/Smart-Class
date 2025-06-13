<?php

namespace App\Models\Kuis;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KuisCobaModel extends Model
{
    use SoftDeletes;

    protected $table = 't_kuis_coba';

    protected $guarded = [];

    /**
     * Cast atribut ke tipe data yang sesuai.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime', // Konversi ke objek Carbon
        'completed_at' => 'datetime', // Konversi ke objek Carbon
    ];

    /**
     * Relasi Many-to-One ke model User.
     * Mendapatkan user yang melakukan percobaan kuis ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-One ke model Quiz.
     * Mendapatkan kuis yang sedang/telah dikerjakan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kuis()
    {
        return $this->belongsTo(KuisModel::class);
    }

    /**
     * Relasi One-to-Many ke model QuizAnswer.
     * Mendapatkan semua detail jawaban untuk percobaan kuis ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jawaban()
    {
        return $this->hasMany(KuisJawabanModel::class);
    }
}
