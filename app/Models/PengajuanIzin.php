<?php

namespace App\Models;

use App\Enums\StatusPengajuan;
use Database\Factories\PengajuanIzinFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'verifikator_id',
        'tanggal_izin',
        'alasan',
        'file_bukti_data',
        'file_bukti_nama',
        'file_bukti_mime',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_izin' => 'date',
            'status' => StatusPengajuan::class,
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', StatusPengajuan::PENDING);
    }

    public function getBuktiBase64Attribute(): string
    {
        return 'data:'.$this->file_bukti_mime.';base64,'.$this->file_bukti_data;
    }

    protected static function newFactory(): PengajuanIzinFactory
    {
        return PengajuanIzinFactory::new();
    }
}
