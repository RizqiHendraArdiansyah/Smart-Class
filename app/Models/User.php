<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Kelas\KelasModel;
use App\Models\Kuis\KuisCobaModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Materi\MateriProgressModel;

class User extends Authenticatable
    // implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function kelas()
    {
        // Parameter kedua adalah nama tabel pivot
        // Parameter ketiga adalah foreign key untuk model ini (User) di tabel pivot
        // Parameter keempat adalah foreign key untuk model lawan (Class) di tabel pivot
        return $this->belongsToMany(KelasModel::class, 't_kelas_det', 'user_id', 'class_id')
            ->withTimestamps(); // Jika ingin mengambil created_at/updated_at dari pivot
    }

    /**
     * Relasi One-to-Many ke model QuizAttempt.
     * Menunjukkan semua percobaan kuis yang pernah dilakukan user ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kuisCoba()
    {
        return $this->hasMany(KuisCobaModel::class);
    }

    public function kelasNew()
    {
        return $this->hasMany(KelasModel::class);
    }

    public function materiProgress()
    {
        return $this->hasMany(MateriProgressModel::class);
    }
}
