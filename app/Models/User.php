<?php

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function isGuru(): bool
    {
        return $this->role === Role::GURU;
    }

    public function isOrangTua(): bool
    {
        return $this->role === Role::ORANG_TUA;
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'orang_tua_id');
    }

    public function verifikasiIzins(): HasMany
    {
        return $this->hasMany(PengajuanIzin::class, 'verifikator_id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
