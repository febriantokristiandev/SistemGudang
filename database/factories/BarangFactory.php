<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        return [
            'nama_barang' => $this->faker->word(),
            'kode' => $this->faker->unique()->word(),
            'kategori' => $this->faker->word(),
            'lokasi' => $this->faker->word(),
            'stok_awal' => $this->faker->numberBetween(1, 100),
            'harga' => $this->faker->numberBetween(1000, 10000),
            'pemasok' => $this->faker->word(),
        ];
    }
}
