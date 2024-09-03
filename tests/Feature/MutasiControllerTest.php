<?php

namespace Tests\Feature;

use App\Models\Mutasi;
use App\Models\User;
use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MutasiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        Mutasi::factory()->create();

        $response = $this->getJson('/api/mutasi', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'tanggal', 'jenis_mutasi', 'jumlah', 'id_barang', 'id_pengguna', 'catatan']
                 ]);
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $barang = Barang::factory()->create(); 
        $token = $user->createToken('testToken')->plainTextToken;
    
        $response = $this->postJson('/api/mutasi', [
            'tanggal' => '2024-09-01 12:00:00',
            'jenis_mutasi' => 'Masuk',
            'jumlah' => 10,
            'id_barang' => $barang->id, // Use the id of the created barang
            'id_pengguna' => $user->id,
            'catatan' => 'Test mutation',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
    
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'tanggal',
                     'jenis_mutasi',
                     'jumlah',
                     'id_barang',
                     'id_pengguna',
                     'catatan',
                 ]);
    
        $this->assertDatabaseHas('mutasi', [
            'jenis_mutasi' => 'Masuk',
            'jumlah' => 10,
            'catatan' => 'Test mutation',
            'id_barang' => $barang->id, // Verify the record exists with the correct id_barang
        ]);
    }
    

    public function test_show()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $mutasi = Mutasi::factory()->create([
            'id_pengguna' => $user->id
        ]);

        $response = $this->getJson('/api/mutasi/' . $mutasi->id, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'tanggal', 'jenis_mutasi', 'jumlah', 'id_barang', 'id_pengguna', 'catatan']);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $mutasi = Mutasi::factory()->create([
            'id_pengguna' => $user->id
        ]);

        $response = $this->putJson('/api/mutasi/' . $mutasi->id, [
            'jenis_mutasi' => 'Keluar',
            'jumlah' => 5,
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['jenis_mutasi' => 'Keluar', 'jumlah' => 5]);

        $this->assertDatabaseHas('mutasi', [
            'id' => $mutasi->id,
            'jenis_mutasi' => 'Keluar',
            'jumlah' => 5,
        ]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $mutasi = Mutasi::factory()->create([
            'id_pengguna' => $user->id
        ]);

        $response = $this->deleteJson('/api/mutasi/' . $mutasi->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Mutasi deleted successfully']);

        $this->assertDatabaseMissing('mutasi', [
            'id' => $mutasi->id,
        ]);
    }
}
