<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $response = $this->postJson('/api/register', [
            'nama' => 'John Doe',
            'email' => 'johndoe@example.com',
            'kata_sandi' => 'password123',
            'alamat' => 'Jl. Merdeka',
            'nomor_telepon' => '081234567890',
            'tanggal_bergabung' => '2024-09-01',
            'peran' => 'admin',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['token']);
        
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    public function test_login()
    {
        User::factory()->create([
            'email' => 'johndoe@example.com',
            'kata_sandi' => Hash::make('password123'),
        ]);
    
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'kata_sandi' => 'password123',
        ]);
    
        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_index()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->getJson('/api/users', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'nama', 'email', 'alamat', 'nomor_telepon', 'tanggal_bergabung', 'peran']
                 ]);
    }

    public function test_store()
    {
        $admin = User::factory()->create();
        $token = $admin->createToken('testToken')->plainTextToken;
    
        $response = $this->postJson('/api/users', [
            'nama' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'kata_sandi' => 'password123',
            'alamat' => 'Jl. Merdeka',
            'nomor_telepon' => '081234567891',
            'tanggal_bergabung' => '2024-09-01',
            'peran' => 'user',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
    
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id', 'nama', 'email', 'alamat', 'nomor_telepon', 'tanggal_bergabung', 'peran', 'token'
                 ]);
    
        $this->assertDatabaseHas('users', [
            'email' => 'janedoe@example.com',
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->getJson('/api/users/' . $user->id, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'nama', 'email', 'alamat', 'nomor_telepon', 'tanggal_bergabung', 'peran']);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->putJson('/api/users/' . $user->id, [
            'nama' => 'Updated Name',
            'email' => 'updatedemail@example.com',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['nama' => 'Updated Name', 'email' => 'updatedemail@example.com']);

        $this->assertDatabaseHas('users', [
            'email' => 'updatedemail@example.com',
        ]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->deleteJson('/api/users/' . $user->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_history_mutasi()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $user->mutasi()->createMany([
            ['tanggal' => '2024-09-01', 'jenis_mutasi' => 'Masuk', 'jumlah' => 10, 'id_barang' => 1, 'catatan' => 'Sample mutation 1'],
            ['tanggal' => '2024-09-02', 'jenis_mutasi' => 'Keluar', 'jumlah' => 5, 'id_barang' => 2, 'catatan' => 'Sample mutation 2'],
        ]);

        $response = $this->getJson('/api/users/' . $user->id . '/mutasi', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'tanggal', 'jenis_mutasi', 'jumlah', 'id_barang', 'id_pengguna', 'catatan']
                 ]);
    }
}
