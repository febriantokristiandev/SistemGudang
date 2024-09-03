<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BarangControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        Barang::factory()->count(3)->create();

        $response = $this->getJson('/api/barang', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'nama_barang',
                         'kode',
                         'kategori',
                         'lokasi',
                         'stok_awal',
                         'harga',
                         'pemasok',
                     ]
                 ]);
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->postJson('/api/barang', [
            'nama_barang' => 'Sample Barang',
            'kode' => 'SB001',
            'kategori' => 'Category A',
            'lokasi' => 'Location A',
            'stok_awal' => 100,
            'harga' => 5000,
            'pemasok' => 'Supplier A',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'nama_barang',
                     'kode',
                     'kategori',
                     'lokasi',
                     'stok_awal',
                     'harga',
                     'pemasok',
                 ]);

        $this->assertDatabaseHas('barang', [
            'kode' => 'SB001',
            'nama_barang' => 'Sample Barang',
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $barang = Barang::factory()->create();

        $response = $this->getJson('/api/barang/' . $barang->id, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'nama_barang',
                     'kode',
                     'kategori',
                     'lokasi',
                     'stok_awal',
                     'harga',
                     'pemasok',
                 ]);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $barang = Barang::factory()->create();

        $response = $this->putJson('/api/barang/' . $barang->id, [
            'nama_barang' => 'Updated Barang',
            'kode' => 'SB002',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'nama_barang' => 'Updated Barang',
                     'kode' => 'SB002',
                 ]);

        $this->assertDatabaseHas('barang', [
            'kode' => 'SB002',
            'nama_barang' => 'Updated Barang',
        ]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $barang = Barang::factory()->create();

        $response = $this->deleteJson('/api/barang/' . $barang->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Barang deleted successfully']);

        $this->assertDatabaseMissing('barang', [
            'id' => $barang->id,
        ]);
    }

    public function test_history_mutasi()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $barang = Barang::factory()->create();
        $barang->mutasi()->createMany([
            ['tanggal' => '2024-09-01 12:00:00', 'jenis_mutasi' => 'Masuk', 'jumlah' => 10, 'id_barang' => $barang->id, 'id_pengguna' => $user->id, 'catatan' => 'Test mutation 1'],
            ['tanggal' => '2024-09-02 12:00:00', 'jenis_mutasi' => 'Keluar', 'jumlah' => 5, 'id_barang' => $barang->id, 'id_pengguna' => $user->id, 'catatan' => 'Test mutation 2'],
        ]);

        $response = $this->getJson('/api/barang/' . $barang->id . '/mutasi', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'tanggal',
                         'jenis_mutasi',
                         'jumlah',
                         'id_barang',
                         'id_pengguna',
                         'catatan',
                     ]
                 ]);
    }
}
