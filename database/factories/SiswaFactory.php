<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        return [
            'orang_tua_id' => User::factory()->orangTua(),
            'nisn' => fake()->unique()->numerify('##########'),
            'nama_siswa' => fake()->name(),
            'kelas' => fake()->randomElement(['1', '2', '3', '4', '5', '6']),
        ];
    }
}
