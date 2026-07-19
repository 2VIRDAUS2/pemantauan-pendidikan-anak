<?php

namespace Database\Factories;

use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Nilai>
 */
class NilaiFactory extends Factory
{
    protected $model = Nilai::class;

    public function definition(): array
    {
        return [
            'siswa_id' => Siswa::factory(),
            'mata_pelajaran' => fake()->randomElement(['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS']),
            'skor' => fake()->randomFloat(2, 0, 100),
        ];
    }
}
