<?php

namespace App\Services;

use App\Models\JadwalPelajaran;
use Illuminate\Support\Collection;

class JadwalService
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function getByKelas(string $kelas): Collection
    {
        return JadwalPelajaran::where('kelas', $kelas)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat')")
            ->orderBy('jam_mulai')
            ->get()
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
