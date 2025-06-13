<?php

namespace App\Models\Kelas;

use App\Models\Modul\ModulModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasModel extends Model
{
    use SoftDeletes;

    protected $table = 'm_kelas';

    protected $guarded = [];

    public function users()
    {
        // Nama model lawan, nama tabel pivot, foreign key model ini, foreign key model lawan
        return $this->belongsToMany(User::class, 't_kelas_det', 'class_id', 'user_id')
            ->withTimestamps(); // Ambil timestamp dari pivot
    }

    public function module() // <-- Tambahkan relasi ini
    {
        return $this->hasMany(ModulModel::class, 'class_id'); // Asumsi nama model Module.php
    }

    public function user() // <-- Tambahkan relasi ini
    {
        return $this->belongsTo(User::class, 'user_id'); // Asumsi nama model Module.php
    }
}
