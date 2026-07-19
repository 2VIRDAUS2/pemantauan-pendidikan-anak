<?php

namespace Database\Seeders;

use App\Models\JadwalPelajaran;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Guru
        $guruMatematika = User::factory()->guru()->create([
            'name' => 'Pak Budi Santoso',
            'email' => 'guru@example.com',
        ]);

        $guruBahasa = User::factory()->guru()->create([
            'name' => 'Ibu Rina Wati',
            'email' => 'guru2@example.com',
        ]);

        // Orang Tua
        $ortu1 = User::factory()->orangTua()->create([
            'name' => 'Bapak Ahmad',
            'email' => 'ortu@example.com',
        ]);

        $ortu2 = User::factory()->orangTua()->create([
            'name' => 'Ibu Dewi',
            'email' => 'ortu2@example.com',
        ]);

        // Siswa Kelas 3
        $andi = Siswa::factory()->create([
            'orang_tua_id' => $ortu1->id,
            'nisn' => '0012345001',
            'nama_siswa' => 'Andi Pratama',
            'kelas' => '3',
        ]);

        $maya = Siswa::factory()->create([
            'orang_tua_id' => $ortu1->id,
            'nisn' => '0012345002',
            'nama_siswa' => 'Maya Putri',
            'kelas' => '3',
        ]);

        $sinta = Siswa::factory()->create([
            'orang_tua_id' => $ortu2->id,
            'nisn' => '0012345003',
            'nama_siswa' => 'Sinta Melati',
            'kelas' => '3',
        ]);

        // Presensi hari ini
        foreach ([$andi, $maya, $sinta] as $siswa) {
            $siswa->presensis()->create([
                'tanggal' => now()->toDateString(),
                'status' => fake()->randomElement(['hadir', 'hadir', 'hadir', 'izin']),
            ]);
        }

        // Presensi 5 hari terakhir
        for ($i = 1; $i <= 5; $i++) {
            foreach ([$andi, $maya, $sinta] as $siswa) {
                $siswa->presensis()->create([
                    'tanggal' => now()->subDays($i)->toDateString(),
                    'status' => fake()->randomElement(['hadir', 'hadir', 'hadir', 'hadir', 'sakit']),
                ]);
            }
        }

        // Nilai untuk Andi
        $mapel = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'PKN', 'PJOK', 'SBdP', 'Agama'];
        foreach ($mapel as $m) {
            $andi->nilais()->create([
                'mata_pelajaran' => $m,
                'skor' => fake()->randomFloat(2, 60, 100),
            ]);
        }

        // Nilai untuk Maya
        foreach ($mapel as $m) {
            $maya->nilais()->create([
                'mata_pelajaran' => $m,
                'skor' => fake()->randomFloat(2, 65, 100),
            ]);
        }

        // Jadwal Kelas 3
        $jadwalSenin = [
            ['jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'mata_pelajaran' => 'Matematika', 'guru_id' => $guruMatematika->id],
            ['jam_mulai' => '08:30', 'jam_selesai' => '09:30', 'mata_pelajaran' => 'Bahasa Indonesia', 'guru_id' => $guruBahasa->id],
            ['jam_mulai' => '09:45', 'jam_selesai' => '10:45', 'mata_pelajaran' => 'IPA', 'guru_id' => $guruMatematika->id],
            ['jam_mulai' => '10:45', 'jam_selesai' => '11:30', 'mata_pelajaran' => 'PJOK', 'guru_id' => $guruMatematika->id],
        ];

        $jadwalSelasa = [
            ['jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'mata_pelajaran' => 'Bahasa Indonesia', 'guru_id' => $guruBahasa->id],
            ['jam_mulai' => '08:30', 'jam_selesai' => '09:30', 'mata_pelajaran' => 'Matematika', 'guru_id' => $guruMatematika->id],
            ['jam_mulai' => '09:45', 'jam_selesai' => '10:45', 'mata_pelajaran' => 'IPS', 'guru_id' => $guruBahasa->id],
            ['jam_mulai' => '10:45', 'jam_selesai' => '11:30', 'mata_pelajaran' => 'SBdP', 'guru_id' => $guruBahasa->id],
        ];

        foreach (['Senin' => $jadwalSenin, 'Selasa' => $jadwalSelasa] as $hari => $jdwls) {
            foreach ($jdwls as $j) {
                JadwalPelajaran::create(array_merge($j, ['kelas' => '3', 'hari' => $hari]));
            }
        }

        // Pengumuman
        Pengumuman::create([
            'pengumum_by' => $guruMatematika->id,
            'judul' => 'Jadwal UTS Semester Genap',
            'isi' => 'Ujian Tengah Semester Genap akan dilaksanakan pada tanggal 15-20 Juli 2026. Semua siswa diharapkan mempersiapkan diri dengan baik.',
            'target_kelas' => null,
            'is_aktif' => true,
        ]);

        Pengumuman::create([
            'pengumum_by' => $guruMatematika->id,
            'judul' => 'Kegiatan Field Trip Kelas 3',
            'isi' => 'Kegiatan field trip ke Taman Sains akan dilaksanakan pada hari Jumat. Mohon izin orang tua.',
            'target_kelas' => '3',
            'is_aktif' => true,
        ]);

        Pengumuman::create([
            'pengumum_by' => $guruBahasa->id,
            'judul' => 'Pengumpulan PR Bahasa Indonesia',
            'isi' => 'Tenggat pengumpulan PR Bab 5 adalah hari Rabu. Mohon diperhatikan.',
            'target_kelas' => '3',
            'is_aktif' => true,
        ]);
    }
}
