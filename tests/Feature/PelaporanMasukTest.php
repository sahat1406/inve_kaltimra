<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\Pelaporan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PelaporanMasukTest extends TestCase
{
    use RefreshDatabase;

    protected $kategori;
    protected $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        // Tambahkan role admin
        Role::create(['id' => 1, 'role' => 'admin']);

        // Buat user admin dan login
        $admin = User::factory()->create(['role_id' => 1]);
        $this->actingAs($admin);

        // Buat data relasi untuk barang
        $this->kategori = Kategori::factory()->create();
        $this->lokasi = Lokasi::factory()->create();
    }

    /** @test */
    public function admin_dapat_melihat_halaman_daftar_pelaporan_masuk()
    {
        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        Pelaporan::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'menunggu',
        ]);

        $response = $this->get('/pelaporan-masuk');
        $response->assertStatus(200);
        $response->assertSee('Pelaporan');
    }

    /** @test */
    public function admin_dapat_melihat_detail_pelaporan()
    {
        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        $pelaporan = Pelaporan::factory()->create([
            'barang_id' => $barang->id,
            'judul' => 'Kerusakan Power Supply',
            'status' => 'menunggu',
        ]);

        $response = $this->get("/pelaporan-masuk/detail/{$pelaporan->id}");
        $response->assertStatus(200);
        $response->assertSee('Kerusakan Power Supply');
    }

    /** @test */
    public function admin_dapat_mengubah_status_pelaporan_ke_sedang_diperbaiki()
    {
        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        $pelaporan = Pelaporan::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'menunggu',
        ]);

        $response = $this->put("/pelaporan-masuk/detail/{$pelaporan->id}/perbaiki");
        $response->assertRedirect();
        $this->assertDatabaseHas('pelaporans', [
            'id' => $pelaporan->id,
            'status' => 'sedang diperbaiki',
        ]);
    }

    /** @test */
    public function admin_dapat_mengubah_status_pelaporan_ke_selesai()
    {
        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        $pelaporan = Pelaporan::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'sedang diperbaiki',
        ]);

        $response = $this->put("/pelaporan-masuk/detail/{$pelaporan->id}/selesai", [
            'pelaporan_id' => $pelaporan->id,
            'analisis_perbaikan' => 'Sudah diganti sparepart',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pelaporans', [
            'id' => $pelaporan->id,
            'status' => 'selesai',
        ]);
    }
}
