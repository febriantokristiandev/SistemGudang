<?php

namespace Database\Factories;

use App\Models\Mutasi;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MutasiFactory extends Factory
{
    protected $model = Mutasi::class;

    public function definition()
    {
        return [
            'tanggal' => $this->faker->dateTimeThisMonth(),
            'jenis_mutasi' => $this->faker->randomElement(['Masuk', 'Keluar']),
            'jumlah' => $this->faker->numberBetween(1, 100),
            'id_barang' => Barang::factory(),
            'id_pengguna' => User::factory(), 
            'catatan' => $this->faker->optional()->sentence(),
        ];
    }
}
