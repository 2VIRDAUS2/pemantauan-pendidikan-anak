<?php

namespace App\Services;

use App\Models\JadwalPelajaran;
use Illuminate\Support\Collection;

class JadwalService
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function getByKelas(string $kelas): Collection
    {
        $urutanHari = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5];

        return JadwalPelajaran::where('kelas', $kelas)
            ->get()
            ->sortBy([
                fn ($a, $b) => ($urutanHari[$a->hari] ?? 99) <=> ($urutanHari[$b->hari] ?? 99),
                fn ($a, $b) => $a->jam_mulai <=> $b->jam_mulai,
            ])
            ->groupBy('hari');
    }

    public function getHariList(): array
    {
        return self::HARI;
    }

    public function store(array $data): JadwalPelajaran
    {
        return JadwalPelajaran::create($data);
    }

    public function delete(JadwalPelajaran $jadwal): bool
    {
        return $jadwal->delete();
    }
}
