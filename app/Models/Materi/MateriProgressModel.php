<?php

namespace App\Models\Materi;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MateriProgressModel extends Model
{
    protected $table = 'm_materi_progress';

    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
        'progress' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materi()
    {
        return $this->belongsTo(MateriModel::class, 'materi_id');
    }
}
