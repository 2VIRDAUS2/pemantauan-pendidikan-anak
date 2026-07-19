<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanIzin;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\PengajuanIzinStatusChanged;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class PengajuanIzinService
{
    public function __construct(
        private readonly FileUploadService $fileUploadService,
    ) {}

    public function create(array $data, UploadedFile $file): PengajuanIzin
    {
        $fileData = $this->fileUploadService->toArray($file);

        return PengajuanIzin::create(array_merge([
            'siswa_id' => $data['siswa_id'],
            'tanggal_izin' => $data['tanggal_izin'],
            'alasan' => $data['alasan'],
            'status' => StatusPengajuan::PENDING,
        ], $fileData));
    }

    public function approve(PengajuanIzin $pengajuan, User $verifikator): PengajuanIzin
    {
        $pengajuan->update([
            'status' => StatusPengajuan::APPROVED,
            'verifikator_id' => $verifikator->id,
        ]);

        $this->syncPresensi($pengajuan);
        $this->notifyOrangTua($pengajuan);

        return $pengajuan->fresh();
    }

    public function reject(PengajuanIzin $pengajuan, User $verifikator): PengajuanIzin
    {
        $pengajuan->update([
            'status' => StatusPengajuan::REJECTED,
            'verifikator_id' => $verifikator->id,
        ]);

        $this->notifyOrangTua($pengajuan);

        return $pengajuan->fresh();
    }

    public function getPendingVerifications(): Collection
    {
        return PengajuanIzin::with(['siswa.orangTua'])
            ->pending()
            ->latest()
            ->get();
    }

    public function getIzinBySiswa(Siswa $siswa): Collection
    {
        return $siswa->pengajuanIzins()->latest()->get();
    }

    public function getIzinByOrangTua(User $orangTua): Collection
    {
        return PengajuanIzin::whereHas('siswa', function ($query) use ($orangTua) {
            $query->where('orang_tua_id', $orangTua->id);
        })->with('siswa')->latest()->get();
    }

    private function syncPresensi(PengajuanIzin $pengajuan): void
    {
        $pengajuan->siswa->presensis()->updateOrCreate(
            ['tanggal' => $pengajuan->tanggal_izin],
            ['status' => 'izin']
        );
    }

    private function notifyOrangTua(PengajuanIzin $pengajuan): void
    {
        $orangTua = $pengajuan->siswa->orangTua;
        $orangTua->notify(new PengajuanIzinStatusChanged($pengajuan));
    }
}
