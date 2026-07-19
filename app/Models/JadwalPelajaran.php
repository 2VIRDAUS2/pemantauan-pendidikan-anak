<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'mata_pelajaran',
        'guru_id',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function scopeForKelas($query, string $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    public function scopeForHari($query, string $hari)
    {
        return $query->where('hari', $hari);
    }
}
