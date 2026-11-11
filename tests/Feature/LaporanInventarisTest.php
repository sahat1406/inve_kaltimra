<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanInventarisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin dan login
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);
    }

    /** @test */
    public function admin_dapat_melihat_halaman_laporan_inventaris()
    {
        $response = $this->get('/laporan-inventaris');

        $response->assertStatus(200);
        $response->assertViewIs('laporan-inventaris.index');
        $response->assertViewHas('barangs');
    }

    /** @test */
    public function admin_dapat_mencetak_laporan_inventaris_dalam_format_pdf()
    {
        // Tambahkan data dummy minimal agar tidak error saat load view PDF
        $kategori = Kategori::factory()->create();
        $lokasi = Lokasi::factory()->create();

        Barang::factory()->create([
            'kategori_id' => $kategori->id,
            'lokasi_id' => $lokasi->id,
        ]);

        $response = $this->get('/laporan-inventaris/laporan');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
