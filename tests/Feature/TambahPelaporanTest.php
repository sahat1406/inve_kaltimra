<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TambahPelaporanTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user dengan role user
        $role = Role::factory()->create(['role' => 'user']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_melihat_form_lapor_kerusakan()
    {
        $response = $this->get('/tambah-pelaporan');

        $response->assertStatus(200);
        $response->assertViewIs('tambah-pelaporan.index');
    }

    /** @test */
    public function user_dapat_mengirimkan_laporan_kerusakan()
    {
        $kategori = Kategori::factory()->create();
        $lokasi = Lokasi::factory()->create();

        $barang = Barang::factory()->create([
            'kategori_id' => $kategori->id,
            'lokasi_id' => $lokasi->id,
        ]);

        $response = $this->post('/tambah-pelaporan', [
            'barang_id' => $barang->id,
            'judul' => 'Kerusakan Layar',
            'deskripsi' => 'Layar mati total saat dinyalakan'
        ]);

        $response->assertRedirect('/tambah-pelaporan');
        $this->assertDatabaseHas('pelaporans', [
            'barang_id' => $barang->id,
            'judul' => 'Kerusakan Layar',
            'deskripsi' => 'Layar mati total saat dinyalakan',
        ]);
    }

    /** @test */
    public function validasi_gagal_jika_judul_atau_deskripsi_kosong()
    {
        $response = $this->post('/tambah-pelaporan', [
            'barang_id' => 1,
            'judul' => '',
            'deskripsi' => ''
        ]);

        $response->assertSessionHasErrors(['judul', 'deskripsi']);
    }
}
