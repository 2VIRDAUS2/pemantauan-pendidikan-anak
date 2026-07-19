<?php

namespace App\Models;

use Database\Factories\NilaiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'mata_pelajaran',
        'skor',
    ];

    protected function casts(): array
    {
        return [
            'skor' => 'float',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    protected static function newFactory(): NilaiFactory
    {
        return NilaiFactory::new();
    }
}
