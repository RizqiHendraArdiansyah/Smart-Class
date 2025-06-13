<?php

namespace App\Models\Kelas;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasDetailModel extends Model
{
    use SoftDeletes;

    protected $table = 't_kelas_det';

    protected $guarded = [];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(KelasModel::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
