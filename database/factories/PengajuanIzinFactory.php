<?php

namespace Database\Factories;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanIzin;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengajuanIzinFactory extends Factory
{
    protected $model = PengajuanIzin::class;

    public function definition(): array
    {
        $fakeContent = fake()->text(100);

        return [
            'siswa_id' => Siswa::factory(),
            'verifikator_id' => null,
            'tanggal_izin' => fake()->dateTimeBetween('+1 days', '+30 days'),
            'alasan' => fake()->sentence(6),
            'file_bukti_data' => base64_encode($fakeContent),
            'file_bukti_nama' => 'bukti.pdf',
            'file_bukti_mime' => 'application/pdf',
            'status' => StatusPengajuan::PENDING,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => StatusPengajuan::PENDING]);
    }

    public function approved(): static
    {
        return $this->state(fn () => ['status' => StatusPengajuan::APPROVED]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => StatusPengajuan::REJECTED]);
    }
}
