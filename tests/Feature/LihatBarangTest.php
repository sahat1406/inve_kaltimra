<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LihatBarangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin
        $role = Role::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_melihat_detail_barang()
    {
        $kategori = Kategori::factory()->create();
        $lokasi   = Lokasi::factory()->create();

        $barang = Barang::create([
            'kd_barang'      => 'BRG-000001',
            'nm_barang'      => 'Proyektor Epson',
            'deskripsi'      => 'Proyektor ruang rapat',
            'tgl_penambahan' => now()->format('Y-m-d'),
            'gambar'         => 'gambar/contoh.jpg',
            'kategori_id'    => $kategori->id,
            'lokasi_id'      => $lokasi->id,
        ]);

        $response = $this->get('/barang/' . $barang->id);

        $response->assertStatus(200);
        $response->assertSeeText('Proyektor Epson');
        $response->assertSeeText('Proyektor ruang rapat');
    }

    /** @test */
    public function melihat_barang_yang_tidak_ada_mengembalikan_404()
    {
        $response = $this->get('/barang/999'); // id yang tidak ada
        $response->assertStatus(404);
    }
}
