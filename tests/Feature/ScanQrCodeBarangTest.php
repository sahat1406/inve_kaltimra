<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScanQrCodeBarangTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role user
        Role::factory()->create(['id' => 2, 'role' => 'user']);

        // Login sebagai user
        $this->user = User::factory()->create([
            'role_id' => 2,
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_mendapatkan_data_barang_dari_qrcode()
    {
        $kategori = Kategori::factory()->create(['kategori' => 'Elektronik']);
        $lokasi   = Lokasi::factory()->create(['lokasi' => 'Gudang A']);


        $barang = Barang::factory()->create([
            'kd_barang'   => 'BRG123',
            'nm_barang'   => 'Laptop Acer',
            'kategori_id' => $kategori->id,
            'lokasi_id'   => $lokasi->id,
        ]);

        $response = $this->getJson('/get-data-barang?result=BRG123');

        $response->assertStatus(200)
            ->assertJson([
                'id'        => $barang->id,
                'nm_barang'=> 'Laptop Acer',
                'kategori' => 'Elektronik',
                'lokasi'   => 'Gudang A',
            ]);
    }

    /** @test */
    public function response_kosong_diberikan_jika_barang_tidak_ditemukan()
    {
        $response = $this->getJson('/get-data-barang?result=TIDAKADA');

        $response->assertStatus(200)
            ->assertJson([
                'id'        => null,
                'nm_barang'=> null,
                'kategori_id' => null,
                'lokasi_id'   => null,
            ]);
    }
}
