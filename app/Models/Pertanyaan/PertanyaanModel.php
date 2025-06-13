<?php

namespace App\Models\Pertanyaan;

use App\Models\Kuis\KuisJawabanModel;
use App\Models\Kuis\KuisModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PertanyaanModel extends Model
{
    use SoftDeletes;

    protected $table = 'm_pertanyaan';

    protected $fillable = [
        'quiz_id',
        'text',
        'type',
        'options',
        'correct_answer',
        'difficulty_level',
        'points',
    ];

    protected $guarded = [
        'class_id',
        'module_id',
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer',
    ];

    /**
     * Relasi Many-to-One ke model Quiz.
     * Mendapatkan kuis pemilik pertanyaan ini.
     */
    public function kuis()
    {
        return $this->belongsTo(KuisModel::class, 'quiz_id');
    }

    /**
     * Relasi One-to-Many ke model QuizAnswer.
     * Mendapatkan semua jawaban yang pernah diberikan untuk pertanyaan ini.
     */
    public function jawaban(): HasMany
    {
        return $this->hasMany(KuisJawabanModel::class, 'question_id');
    }
}
