<?php

namespace App\Services;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Collection;

class NilaiService
{
    public function store(array $data): Nilai
    {
        return Nilai::updateOrCreate(
            [
                'siswa_id' => $data['siswa_id'],
                'mata_pelajaran' => $data['mata_pelajaran'],
            ],
            ['skor' => $data['skor']]
        );
    }

    public function getBySiswa(Siswa $siswa): Collection
    {
        return $siswa->nilais()->orderBy('mata_pelajaran')->get();
    }

    public function getByOrangTua(User $orangTua): Collection
    {
        return Nilai::whereHas('siswa', function ($query) use ($orangTua) {
            $query->where('orang_tua_id', $orangTua->id);
        })->with('siswa')->orderBy('mata_pelajaran')->get();
    }

    public function update(Nilai $nilai, array $data): Nilai
    {
        $nilai->update($data);

        return $nilai->fresh();
    }

    public function delete(Nilai $nilai): bool
    {
        return $nilai->delete();
    }
}
