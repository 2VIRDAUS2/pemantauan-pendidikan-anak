<?php

namespace App\Services;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Support\Collection;

class PengumumanService
{
    public function getAktif(?string $kelas = null): Collection
    {
        return Pengumuman::aktif()
            ->untukKelas($kelas)
            ->latest()
            ->get();
    }

    public function getAll(): Collection
    {
        return Pengumuman::with('pembuat')->latest()->get();
    }

    public function store(array $data, User $pembuat): Pengumuman
    {
        return Pengumuman::create(array_merge(
            $data,
            ['pengumum_by' => $pembuat->id]
        ));
    }

    public function toggleStatus(Pengumuman $pengumuman): Pengumuman
    {
        $pengumuman->update(['is_aktif' => ! $pengumuman->is_aktif]);

        return $pengumuman->fresh();
    }
}
