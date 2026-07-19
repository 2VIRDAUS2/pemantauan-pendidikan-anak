<?php

namespace App\Models;

use Database\Factories\SiswaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'orang_tua_id',
        'nisn',
        'nama_siswa',
        'kelas',
    ];

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orang_tua_id');
    }

    public function pengajuanIzins(): HasMany
    {
        return $this->hasMany(PengajuanIzin::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    public function getPresensiHariIniAttribute(): ?Presensi
    {
        return $this->presensis()->whereDate('tanggal', now()->toDateString())->first();
    }

    public function getRataRataNilaiAttribute(): float
    {
        $avg = $this->nilais()->avg('skor');

        return $avg ? round($avg, 2) : 0;
    }

    protected static function newFactory(): SiswaFactory
    {
        return SiswaFactory::new();
    }
}
