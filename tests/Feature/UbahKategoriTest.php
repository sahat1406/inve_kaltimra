<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UbahKategoriTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin dan login
        $role = Role::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_mengubah_data_kategori_dengan_input_valid()
    {
        // Buat kategori awal
        $kategori = Kategori::factory()->create([
            'kategori' => 'Elektronik Lama',
        ]);

        // Data baru
        $dataBaru = [
            'kategori' => 'Elektronik Baru',
        ];

        // Kirim PUT request
        $response = $this->put("/kategori/{$kategori->id}", $dataBaru);

        // Redirect ke halaman kategori
        $response->assertRedirect('/kategori');

        // Cek perubahan data di database
        $this->assertDatabaseHas('kategoris', [
            'id' => $kategori->id,
            'kategori' => 'Elektronik Baru',
        ]);
    }

    /** @test */
    public function tidak_bisa_update_dengan_nama_kategori_yang_sama()
    {
        // Buat dua kategori
        Kategori::factory()->create(['kategori' => 'Komputer']);
        $kategori2 = Kategori::factory()->create(['kategori' => 'Laptop']);

        // Coba ubah kategori2 menjadi "Komputer" yang sudah ada
        $response = $this->put("/kategori/{$kategori2->id}", [
            'kategori' => 'Komputer',
        ]);

        // Harus kembali karena gagal validasi
        $response->assertSessionHasErrors('kategori');

        // Pastikan data belum berubah
        $this->assertDatabaseHas('kategoris', [
            'id' => $kategori2->id,
            'kategori' => 'Laptop',
        ]);
    }
}
