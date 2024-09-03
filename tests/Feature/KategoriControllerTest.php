<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        Kategori::factory()->count(3)->create();

        $response = $this->getJson('/api/kategori', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'nama_kategori',
                         'deskripsi',
                     ]
                 ]);
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $response = $this->postJson('/api/kategori', [
            'nama_kategori' => 'Sample Category',
            'deskripsi' => 'Sample description',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'nama_kategori',
                     'deskripsi',
                 ]);

        $this->assertDatabaseHas('kategori', [
            'nama_kategori' => 'Sample Category',
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $kategori = Kategori::factory()->create();

        $response = $this->getJson('/api/kategori/' . $kategori->id, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'nama_kategori',
                     'deskripsi',
                 ]);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $kategori = Kategori::factory()->create();

        $response = $this->putJson('/api/kategori/' . $kategori->id, [
            'nama_kategori' => 'Updated Category',
            'deskripsi' => 'Updated description',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'nama_kategori' => 'Updated Category',
                     'deskripsi' => 'Updated description',
                 ]);

        $this->assertDatabaseHas('kategori', [
            'nama_kategori' => 'Updated Category',
        ]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        $kategori = Kategori::factory()->create();

        $response = $this->deleteJson('/api/kategori/' . $kategori->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Kategori deleted successfully']);

        $this->assertDatabaseMissing('kategori', [
            'id' => $kategori->id,
        ]);
    }
}
