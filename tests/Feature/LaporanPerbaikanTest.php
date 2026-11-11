<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pelaporan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanPerbaikanTest extends TestCase
{
    use RefreshDatabase;

    protected $kategori;
    protected $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup role admin
        Role::factory()->create([
            'id' => 1,
            'role' => 'admin'
        ]);

        // Setup kategori dan lokasi
        $this->kategori = Kategori::factory()->create();
        $this->lokasi   = Lokasi::factory()->create();

        // Login sebagai admin
        $admin = User::factory()->create([
            'role_id' => 1
        ]);
        $this->actingAs($admin);
    }

    /** @test */
    public function admin_dapat_mencetak_laporan_perbaikan()
    {
        // Buat barang
        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'lokasi_id'   => $this->lokasi->id,
        ]);

        // Buat pelaporan selesai
        Pelaporan::factory()->create([
            'barang_id' => $barang->id,
            'user_id'   => auth()->id(),
            'status'    => 'selesai',
        ]);

        // Hit endpoint cetak laporan
        $response = $this->get('/laporan-perbaikan/laporan');

        // Cek respons
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
