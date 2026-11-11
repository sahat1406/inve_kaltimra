<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TambahKategoriTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role admin dan login sebagai admin
        $role = Role::create(['role' => 'admin']);

        $this->user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_menambah_kategori_baru_dengan_data_valid()
    {
        $response = $this->post('/kategori', [
            'kategori' => 'Elektronik',
        ]);

        $response->assertRedirect('/kategori');
        $response->assertSessionHas('success', 'Berhasil menambahkan kategori baru');

        $this->assertDatabaseHas('kategoris', [
            'kategori' => 'Elektronik',
        ]);
    }

    /** @test */
    public function tambah_kategori_gagal_jika_input_kosong()
    {
        $response = $this->post('/kategori', []);

        $response->assertSessionHasErrors(['kategori']);
    }

    /** @test */
    public function tambah_kategori_gagal_jika_kategori_sudah_ada()
    {
        Kategori::create(['kategori' => 'Elektronik']);

        $response = $this->post('/kategori', [
            'kategori' => 'Elektronik',
        ]);

        $response->assertSessionHasErrors(['kategori']);
    }
}
